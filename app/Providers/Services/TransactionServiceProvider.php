<?php

namespace App\Providers\Services;

use App\Services\Interfaces\TransactionServiceInterface;
use App\Services\TransactionService;
use Illuminate\Support\ServiceProvider;

class TransactionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TransactionServiceInterface::class, TransactionService::class);
    }
}
