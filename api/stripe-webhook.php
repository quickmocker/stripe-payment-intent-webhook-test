<?php

namespace api;

use api\extra\DB;
use api\extra\RequestHandler;
use api\extra\StripeBase;
use Exception;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Webhook;

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
            $event = Webhook::constructEvent(
                $payload,
                $signature_header,
                StripeBase::getInstance()->signingSecret,
            );
            $intent = $event->data->object;
            DB::getInstance()->updateIntent([
                'id' => $intent->id,
                'amount' => $intent->amount,
                'status' => $intent->status,
            ]);
            $requestHandler->return([
                'result' => 'Webhook Signal Successfully Processed',
            ]);
        } catch (UnexpectedValueException $e) {
            $requestHandler->return('Stripe Invalid Payload', RequestHandler::STATUS_ERR);
        } catch (SignatureVerificationException $e) {
            $requestHandler->return('Stripe Invalid Signature', RequestHandler::STATUS_ERR);
        } catch (Exception $e) {
            $requestHandler->return('Stripe Error: ' . $e->getMessage(), RequestHandler::STATUS_ERR);
        }
    }
};
