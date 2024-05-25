<?php

namespace App\Services\Interfaces;

use App\Enums\TransactionPaymentMethodEnum;

interface TransactionServiceInterface
{
    public function saveHistory(
        int $senderUserId,
        string $transactionId,
        string $amount,
        string $gateway,
        int $recipientUserId,
        ?TransactionPaymentMethodEnum $paymentMethod = null
    ): string;
}
