<?php

namespace App\Services;

use App\Enums\TransactionPaymentMethodEnum;
use App\Enums\TransactionStatusEnum;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Services\Interfaces\TransactionServiceInterface;

class TransactionService implements TransactionServiceInterface
{
    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepository,
    ) {
    }

    public function saveHistory(
        int $senderUserId,
        string $transactionId,
        string $amount,
        string $gateway,
        int $recipientUserId,
        ?TransactionPaymentMethodEnum $paymentMethod = null,
    ): string {
        return $this->transactionRepository->register([
            'user_sender_id' => $senderUserId,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'payment_method' => data_get($paymentMethod, 'value'),
            'status' => TransactionStatusEnum::PENDING,
            'user_recipient_id' => $recipientUserId,
        ]);
    }
}
