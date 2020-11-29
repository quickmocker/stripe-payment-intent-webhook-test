<?php

namespace api;

require_once '../vendor/autoload.php';

// error_reporting(E_ALL);
// ini_set('display_errors', true);

use api\extra\RequestHandler;
use api\extra\StripeBase;
use api\extra\DB;

new class
{
    public $stripe;

    public function __construct()
    {
        // Get and validate request input.
        $requestHandler = new RequestHandler();
        $data = $requestHandler->getInput();
        $requestHandler->validate($data);

        // Generate a random payment amount.
        if (empty($data['id'])) {
            $data = array_merge($data, [
                'amount' => rand(50, 100),
                'currency' => 'usd',
            ]);
        }
        try {
            // Check if email already exist and if yes use its customer id.
            if (!empty($data['email']) && ($existingIntent = DB::getInstance()->findByEmail($data['email']))) {
                $data['customer_id'] = $existingIntent['customer_id'];
            }

            // Create or update remote payment intent.
            $this->stripe = new StripeBase();
            $intent = $this->stripe->createOrUpdateIntent($data);

            // Store payment intent in the local database for further re-use.
            $data['id'] = $intent['id'];
            $data['client_secret'] = $intent['client_secret'];
            $data['status'] = $intent['status'];
            $data['customer_id'] = $intent['customer'];
            DB::getInstance()->insertOrUpdateIntent($data);

            // Return the data to client.
            $requestHandler->return([
                'id' => $intent['id'],
                'client_secret' => $intent['client_secret'],
                'amount' => $intent['amount'],
            ], RequestHandler::STATUS_OK);
        } catch (\Exception $e) {
            $requestHandler->return('Stripe: ' . $e->getMessage(), RequestHandler::STATUS_ERR);
        }
    }
};