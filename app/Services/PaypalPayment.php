<?php

namespace App\Services;
use App\Contracts\PaymentInterface;

class PaypalPayment implements PaymentInterface{
    public function pay(){
        return "paid via paypal";
    }
}