<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function __construct(
        private readonly Transaction $model,
    ) {
    }

    public function register(array $data): string
    {
        $transaction = $this->model->create($data);

        return data_get($transaction, 'id');
    }
}
