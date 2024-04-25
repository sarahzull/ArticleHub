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
        $this->app->singleton(WebhookService::class, function (Application $app) {;
            return new WebhookService(
                $app->make(SubscriptionService::class), 
                $app->make(SubscriptionPlanService::class),
            );
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
