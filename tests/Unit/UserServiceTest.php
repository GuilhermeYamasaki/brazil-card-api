<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\GenerateTokenService;
use App\Services\Interfaces\UserServiceInterface;
use RuntimeException;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    public function test_must_register_new_user_success(): void
    {
        // Arrange
        $userModel = User::factory()->make();
        $password = fake()->password();
        $userModel->passowd = bcrypt($password);

        $tokenExpected = fake()->uuid();

        $userRepositoryMock = $this->mock(UserRepositoryInterface::class, function ($mock) use ($userModel) {
            $mock->shouldReceive('register')
                ->andReturn($userModel);

            $mock->shouldReceive('findByEmail')
                ->with($userModel->email)
                ->andReturn(null);
        });

        $generateTokenServiceMock = $this->mock(GenerateTokenService::class, function ($mock) use ($userModel, $tokenExpected) {
            $mock->shouldReceive('handle')
                ->with($userModel)
                ->andReturn($tokenExpected);
        });

        $userService = resolve(UserServiceInterface::class);

        // Act
        $data = $userService->register([
            ...$userModel->toArray(),
            'password' => $password,
        ]);

        // Assert
        $this->assertArrayHasKey('token', $data);
        $this->assertEquals($data['token'], $tokenExpected);

        $userRepositoryMock->shouldHaveReceived('findByEmail')
            ->once();

        $userRepositoryMock->shouldHaveReceived('register')
            ->once();

        $generateTokenServiceMock->shouldHaveReceived('handle')
            ->once();
    }

    public function test_must_exists_email_when_register_new_user(): void
    {
        // Assert
        $this->expectException(RuntimeException::class);

        // Arrange
        $email = fake()->email();

        $userRepositoryMock = $this->mock(UserRepositoryInterface::class, function ($mock) use ($email) {
            $mock->shouldReceive('findByEmail')
                ->with($email)
                ->andReturn((object) ['email' => $email]);
        });

        $userService = resolve(UserServiceInterface::class);

        // Act
        $userService->register([
            'email' => $email,
        ]);

        // Assert
        $userRepositoryMock->shouldHaveReceived('findByEmail')
            ->once();

        $userRepositoryMock->shouldHaveReceived('register')
            ->times(0);
    }
}
