<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use RuntimeException;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly GenerateTokenService $generateTokenService
    ) {
    }

    public function register(array $data): array
    {
        $emailExists = $this->userRepository->findByEmail($data['email']);

        if ($emailExists) {
            throw new RuntimeException('Email already exists');
        }

        $data['password'] = bcrypt($data['password']);

        $user = $this->userRepository->register($data);
        $token = $this->generateTokenService->handle($user);

        return [
            ...$user->toArray(),
            'token' => $token,
        ];
    }

    public function canTransfer(int $userId, float $amount): bool
    {
        $user = $this->userRepository->findById($userId);

        return $user->money >= $amount;
    }
}
