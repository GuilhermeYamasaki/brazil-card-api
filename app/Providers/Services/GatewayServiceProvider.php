<?php

namespace App\Providers\Services;

use App\Services\Interfaces\AsaasServiceInterface;
use App\Services\Interfaces\GatewayServiceInterface;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class GatewayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(GatewayServiceInterface::class, function () {
            return match (config('gateway.use')) {
                'asaas' => app(AsaasServiceInterface::class),
                default => throw new RuntimeException('Selecionar gateway no Env')
            };
        });
    }
}
