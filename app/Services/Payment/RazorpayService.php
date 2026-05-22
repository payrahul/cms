<?php

namespace App\Services\Payment;

class RazorpayService implements PaymentInterface
{
    public function pay($amount)
    {
        return "Payment done using Razorpay: $" . $amount;
    }
}