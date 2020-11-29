<?php

namespace api\extra;

class RequestHandler
{

    const STATUS_OK = 200;
    const STATUS_ERR = 400;

    public function getInput()
    {
        $input = file_get_contents('php://input') ?: '{}';
        return json_decode($input, true);
    }

    public function return($data, $code)
    {
        header('Content-Type: application/json');
        http_response_code($code);

        if ($code != self::STATUS_OK) {
            echo json_encode([
                'error' => $data,
            ]);
            exit;
        }
        echo json_encode($data);
        exit;
    }

    public function validate($data)
    {
        if (empty($data['email'])) {
            $this->return('Missing email', self::STATUS_ERR);
        }
        if (empty($data['name'])) {
            $this->return('Missing name', self::STATUS_ERR);
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->return('Invalid email address', self::STATUS_ERR);
        }
    }
}
