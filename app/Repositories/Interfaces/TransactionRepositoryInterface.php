<?php

namespace App\Repositories\Interfaces;

interface TransactionRepositoryInterface
{
    public function register(array $data): string;
}
