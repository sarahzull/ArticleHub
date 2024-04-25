<?php

namespace App\Providers;

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
        // $this->app->singleton(WebhookService::class, function (Application $app) {;
        //     return new WebhookService(
        //         $app->make(SubscriptionService::class), 
        //         $app->make(SubscriptionPlanService::class),
        //     );
        // });
        $this->app->singleton(WebhookService::class, function (Application $app) {
            $subscriptionService = $app->make(SubscriptionService::class);
            $subscriptionPlanService = $app->make(SubscriptionPlanService::class);

            return new WebhookService($subscriptionPlanService, $subscriptionService);
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
