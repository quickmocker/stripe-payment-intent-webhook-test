<?php

namespace api\extra;

use Exception;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeBase
{
    static $instance;

    public $signingSecret;

    static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $config = require dirname(__DIR__) . '/config.php';
        if (empty($config['stripe'])) {
            throw new Exception('No Stripe Configuration Found.');
        }
        Stripe::setApiKey($config['stripe']['secretKey']);
        $this->signingSecret = $config['stripe']['signingSecret'];
    }

    public function createOrUpdateIntent($data)
    {
        if (!empty($data['customer_id'])) {
            $data['customer'] = $this->getCustomer($data['customer_id']);
        }

        if (!empty($data['email']) && empty($data['customer_id'])) {
            $data['customer'] = $this->createCustomer([
                'email' => $data['email'],
                'name' => !empty($data['name']) ? $data['name'] : '',
            ]);
        }

        $intentData = array_intersect_key($data, array_flip([
            'amount',
            'currency',
            'customer',
        ]));
        if (empty($data['id'])) {
            return PaymentIntent::create($intentData);
        } else {
            return PaymentIntent::update($data['id'], $intentData);
        }
    }

    public function getCustomer($id)
    {
        return Customer::retrieve($id);
    }

    public function createCustomer($data)
    {
        return Customer::create($data);
    }
}
