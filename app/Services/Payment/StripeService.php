<?php

namespace App\Services\Payment;

class StripeService implements PaymentInterface
{
    public function pay($amount)
    {
        return "Payment done using Stripe: $" . $amount;
    }
}