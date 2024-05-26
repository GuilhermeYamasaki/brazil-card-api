<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly User $model
    ) {

    }

    public function register(array $data): object
    {
        return $this->model->create($data);
    }

    public function findByEmail(string $email): ?object
    {
        return $this->model->where('email', $email)->first();
    }

    public function findById(int $id): ?object
    {
        return $this->model->find($id);
    }

    public function addMoney(int $userId, float $amount): void
    {
        $this->model->find($userId)->increment('money', $amount);
    }

    public function subtractMoney(int $userId, float $amount): void
    {
        $this->model->find($userId)->decrement('money', $amount);
    }
}
