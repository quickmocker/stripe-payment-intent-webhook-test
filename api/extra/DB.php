<?php

namespace api\extra;

use Exception;
use PDO;

class DB
{
    static $instance;

    private $_pdo;

    private $paymentIntentColumns = [
        'id',
        'name',
        'email',
        'amount',
        'client_secret',
        'customer_id',
        'status',
        'created_at',
        'updated_at',
    ];

    public function __construct()
    {
        $config = require dirname(__DIR__) . '/config/config.php';
        if (empty($config['db'])) {
            throw new Exception('DB configuration not found.');
        }
        $this->_pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['password']);
    }

    public function getPDO()
    {
        return $this->_pdo;
    }

    /**
     * @return DB
     */
    static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function findbyEmail($email)
    {
        $statement = $this->_pdo->prepare('SELECT * FROM payment_intent WHERE email = :email AND (customer_id IS NOT NULL OR customer_id <> "" )');
        $statement->execute(['email' => $email]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function insertIntent($intent)
    {
        $intent['created_at'] = date('Y-m-d H:i:s');
        $intent['updated_at'] = date('Y-m-d H:i:s');
        $columnsString = implode(',', $this->paymentIntentColumns);
        $valueParams = array_map(function ($column) {
            return ':' . $column;
        }, $this->paymentIntentColumns);
        $valuesString = implode(',', $valueParams);
        $statement = $this->_pdo->prepare("INSERT INTO payment_intent ($columnsString) VALUES($valuesString)");
        $finalIntent = array_intersect_key($intent, array_flip($this->paymentIntentColumns));
        $statement->execute($finalIntent);
    }

    public function updateIntent($intent)
    {
        $intent['updated_at'] = date('Y-m-d H:i:s');
        $updates = [];
        foreach ($intent as $param => $value) {
            if (in_array($param, $this->paymentIntentColumns)) {
                $updates[] = sprintf('%s = :%s', $param, $param);
            }
        }
        $updatesString = implode(',', $updates);
        $statement = $this->_pdo->prepare("UPDATE payment_intent SET $updatesString WHERE id = :id");
        $statement->execute($intent);
    }

    public function findById($id)
    {
        $statement = $this->_pdo->prepare('SELECT * FROM payment_intent WHERE id = :id');
        $statement->execute(['id' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function insertOrUpdateIntent($intent)
    {
        if ($this->findById($intent['id'])) {
            $this->updateIntent($intent);
        } else {
            $this->insertIntent($intent);
        }
    }
}
