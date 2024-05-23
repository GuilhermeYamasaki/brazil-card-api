<?php

namespace App\Providers\Services;

use App\Services\Interfaces\UserServiceInterface;
use App\Services\UserService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserServiceInterface::class,
            UserService::class
        );
    }

    /**
     * @return string[]
     */
    public function provides()
    {
        return [
            UserServiceInterface::class,
        ];
    }
}
