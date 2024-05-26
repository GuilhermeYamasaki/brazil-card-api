<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use App\Repositories\AsaasRepository;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoneyTransferControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_must_sender_success_transfer_money_to_recipient(): void
    {
        // Arrange
        $amountExpected = fake()->randomFloat(2, 1, 1000);

        $senderUser = User::factory()->create([
            'money' => $amountExpected,
        ]);
        $recipientUser = User::factory()->create();
        $transactionIdExpected = fake()->randomNumber();

        $this->mock(AsaasRepository::class, function ($mock) use ($senderUser, $amountExpected, $transactionIdExpected) {
            $customerId = fake()->randomNumber();

            $mock->shouldReceive('findCustomerByEmail')
                ->with($senderUser->email)
                ->andReturn([
                    'id' => $customerId,
                ]);

            $mock->shouldReceive('createTransaction')
                ->with([
                    'customer' => $customerId,
                    'billingType' => 'UNDEFINED',
                    'value' => $amountExpected,
                    'description' => 'Cobrança gerada',
                    'dueDate' => now()->addDay()->format('Y-m-d'),
                ])
                ->andReturn([
                    'id' => $transactionIdExpected,
                ]);
        });

        // Act
        $response = $this->postJson(
            route('money.transfer'),
            [
                'recipientUserId' => $recipientUser->id,
                'amount' => $amountExpected,
            ],
            $this->generateHeaderToken($senderUser)
        );

        // Assert
        $response->assertOk()
            ->assertJsonStructure([
                'history_id',
            ]);

        $this->assertDatabaseHas((new Transaction)->getTable(), [
            'user_sender_id' => $senderUser->id,
            'user_recipient_id' => $recipientUser->id,
            'amount' => $amountExpected,
            'transaction_id' => $transactionIdExpected,
        ]);
    }

    public function test_must_not_transfer_with_error_in_gateway(): void
    {
        // Arrange
        $amountExpected = fake()->randomFloat(2, 1, 1000);

        $senderUser = User::factory()->create([
            'money' => $amountExpected,
        ]);
        $recipientUser = User::factory()->create();
        $errorExpected = fake()->sentence();

        $this->mock(AsaasRepository::class, function ($mock) use ($senderUser, $errorExpected) {
            $customerId = fake()->randomNumber();

            $mock->shouldReceive('findCustomerByEmail')
                ->with($senderUser->email)
                ->andThrows(new Exception($errorExpected));
        });

        // Act
        $response = $this->postJson(
            route('money.transfer'),
            [
                'recipientUserId' => $recipientUser->id,
                'amount' => $amountExpected,
            ],
            $this->generateHeaderToken($senderUser)
        );

        // Assert
        $response->assertInternalServerError()
            ->assertExactJson([
                'message' => $errorExpected,
            ]);
    }

    public function test_must_not_transfer_money_of_user_enought_in_wallet(): void
    {
        // Arrange
        $amountExpected = fake()->randomFloat(2, 1, 1000);

        $senderUser = User::factory()->create([
            'money' => $amountExpected - 1,
        ]);
        $recipientUser = User::factory()->create();

        // Act
        $response = $this->postJson(
            route('money.transfer'),
            [
                'recipientUserId' => $recipientUser->id,
                'amount' => $amountExpected,
            ],
            $this->generateHeaderToken($senderUser)
        );

        // Assert
        $response->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function test_must_not_transfer_money_of_not_exists_recipient_user(): void
    {
        // Arrange
        $amountExpected = fake()->randomFloat(2, 1, 1000);

        $senderUser = User::factory()->create([
            'money' => $amountExpected,
        ]);

        $recipientUserId = fake()->randomNumber(3);

        // Act
        $response = $this->postJson(
            route('money.transfer'),
            [
                'recipientUserId' => $recipientUserId,
                'amount' => $amountExpected,
            ],
            $this->generateHeaderToken($senderUser)
        );

        // Assert
        $response->assertUnprocessable()
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function test_must_unathorized_request_to_transfer_money(): void
    {
        // Act
        $response = $this->postJson(
            route('money.transfer'),
            []
        );

        // Assert
        $response->assertUnauthorized()
            ->assertJsonStructure([
                'message',
            ]);
    }
}
