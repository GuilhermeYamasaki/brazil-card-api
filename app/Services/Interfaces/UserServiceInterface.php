<?php

namespace App\Services\Interfaces;

interface UserServiceInterface
{
    public function register(array $data): array;
}
