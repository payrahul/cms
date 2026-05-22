<?php

namespace App\Providers;
use App\Services\UserService;
use App\Contracts\PaymentInterface;
use App\Services\StripePayment;
use App\Services\PaypalPaymet;
use App\Services\GreetingService;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        

        $this->app->bind(\App\Contracts\PaymentInterface::class, function ($app, $params) {

            $methods = [
                'stripe' => \App\Services\StripePayment::class,
                'paypal' => \App\Services\PaypalPayment::class,
            ];

            $method = $params['method'] ?? 'stripe';

            return $app->make($methods[$method]);
        });

         // ✅ GreetingService binding (added)
        $this->app->bind('greetings', function () {
            return new GreetingService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
