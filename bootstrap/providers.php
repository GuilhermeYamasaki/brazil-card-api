<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Repositories\UserRepositoryProvider::class,
    App\Providers\Services\UserServiceProvider::class,
    App\Providers\Services\AsaasServiceProvider::class,
    App\Providers\Repositories\AsaasRepositoryProvider::class,
    App\Providers\Repositories\TransactionRepositoryProvider::class,
    App\Providers\Services\TransactionServiceProvider::class,
];
