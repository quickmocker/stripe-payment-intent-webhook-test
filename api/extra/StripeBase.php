<?php

namespace api\extra;

use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeBase
{
    // Replace with your own secret key.
    public $stripeSecretKey = 'sk_test_51HrW5DBqxL3ZMOsUW7pcmeRQsFS4MctewnYTXY3WmIvQ4Xh7BWand5yDFlye9qFA8GUrs7VZOAstKH28uOG1Q50D00IZvoKURM';

    public function __construct()
    {
        Stripe::setApiKey($this->stripeSecretKey);
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
