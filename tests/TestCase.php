<?php

namespace Tests;

use App\Models\User;
use App\Services\GenerateTokenService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function generateHeaderToken(User $user): array
    {
        $token = resolve(GenerateTokenService::class)->handle($user);

        return [
            'Authorization' => "Bearer {$token}",
        ];
    }
}
