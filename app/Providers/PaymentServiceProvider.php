<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Payment\RazorpayService;
use App\Services\Payment\PaymentInterface;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentInterface::class,RazorpayService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
