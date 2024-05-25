<?php

namespace App\Services\Interfaces;

use App\Enums\TransactionPaymentMethodEnum;

interface TransactionServiceInterface
{
    public function saveHistory(
        int $senderUserId,
        string $transactionId,
        string $amount,
        TransactionPaymentMethodEnum $paymentMethod,
        string $gateway,
        int $recipientUserId
    ): string;
}
