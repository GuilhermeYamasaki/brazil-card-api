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
}
