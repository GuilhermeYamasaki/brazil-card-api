<?php

namespace App\Providers\Services;

use App\Services\AsaasService;
use App\Services\Interfaces\AsaasServiceInterface;
use App\Services\Interfaces\GatewayServiceInterface;
use Illuminate\Support\ServiceProvider;

class AsaasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(GatewayServiceInterface::class, AsaasService::class);
        $this->app->singleton(AsaasServiceInterface::class, AsaasService::class);
    }
}
