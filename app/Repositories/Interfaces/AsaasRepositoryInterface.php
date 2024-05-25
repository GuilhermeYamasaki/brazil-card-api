<?php

namespace App\Repositories\Interfaces;

interface AsaasRepositoryInterface
{
    public function findCustomerByEmail(string $email): ?array;

    public function createCustomer(array $customerData): array;

    public function createTransaction(array $transactionData): array;
}
