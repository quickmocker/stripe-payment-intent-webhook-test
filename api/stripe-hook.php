<?php

namespace api;

use api\extra\DB;
use api\extra\RequestHandler;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;

require_once '../vendor/autoload.php';

new class
{

    public $signing_secret = 'whsec_qNgiZhL5o80m9LXg3zce3kwCM2GLX4Kp';

    public $stripe;

    public function __construct()
    {
        $requestHandler = new RequestHandler('post');
        $payload = @file_get_contents('php://input');
        $signature_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signature_header,
                $this->signing_secret
            );
        } catch (UnexpectedValueException $e) {
            $requestHandler->return('Stripe Invalid Payload', RequestHandler::STATUS_ERR);
        } catch (SignatureVerificationException $e) {
            $requestHandler->return('Stripe Invalid Signature', RequestHandler::STATUS_ERR);
        }

        $intent = $event->data->object;
        DB::getInstance()->updateIntent([
            'id' => $intent->id,
            'amount' => $intent->amount,
            'status' => $intent->status,
        ]);
    }
};
