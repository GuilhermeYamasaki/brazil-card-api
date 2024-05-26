<?php

namespace App\Repositories\Interfaces;

interface TransactionRepositoryInterface
{
    public function register(array $data): string;

    public function findByTransaction(string $transactionId): array;

    public function update(string $transactionId, array $data): void;
}
