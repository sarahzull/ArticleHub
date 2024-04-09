<?php

namespace App\Providers;

use App\Services\XsollaClient;
use App\Services\XsollaService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class XsollaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(XsollaService::class, function (Application $app) {;
            return new XsollaService(
                $app->make(XsollaClient::class), 
                config('services.xsolla.project_id'), 
                config('app.url')
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
