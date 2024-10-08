<?php

namespace App\Providers;

use App\Services\UserService;
use App\Services\WebhookService;
use App\Services\SubscriptionService;
use Illuminate\Support\ServiceProvider;
use App\Services\SubscriptionPlanService;
use Illuminate\Contracts\Foundation\Application;

class WebhookServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(WebhookService::class, function (Application $app) {
            $subscriptionPlanService = $app->make(SubscriptionPlanService::class);
            $subscriptionService = $app->make(SubscriptionService::class);
            $userService = $app->make(UserService::class);

            return new WebhookService($subscriptionPlanService, $subscriptionService, $userService);
        });
    }
    

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
