<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function register(array $data): object;

    public function findByEmail(string $email): ?object;

    public function findById(int $id): ?object;

    public function addMoney(int $userId, float $amount): void;

    public function subtractMoney(int $userId, float $amount): void;
}
