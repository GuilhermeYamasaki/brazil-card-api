<?php

namespace App\Providers\Repositories;

use App\Repositories\AsaasRepository;
use App\Repositories\Interfaces\AsaasRepositoryInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class AsaasRepositoryProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AsaasRepositoryInterface::class, AsaasRepository::class);
    }

    /**
     * @return string[]
     */
    public function provides()
    {
        return [
            AsaasRepositoryInterface::class,
        ];
    }
}
