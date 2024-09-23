<?php

namespace App\Providers;

use App\Services\XsollaClient;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class XsollaClientProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(XsollaClient::class, function (Application $app) {
            return new XsollaClient(
                config('services.xsolla.merchant_id'), 
                config('services.xsolla.project_id'), 
                config('services.xsolla.api_key'), 
                config('services.xsolla.api_url'),
                config('services.xsolla.ps4_base_url')
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
