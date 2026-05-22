<?php

namespace App\Services;
use App\Contracts\PaymentInterface;

class StripePayment implements PaymentInterface
{
    public function pay(){
        return "paid va stripe";
    }
}
