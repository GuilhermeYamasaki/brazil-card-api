<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function register(array $data): object;

    public function findByEmail(string $email): ?object;
}
