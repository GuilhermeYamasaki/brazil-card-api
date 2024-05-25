<?php

namespace App\Services\Interfaces;

use App\Enums\TransactionPaymentMethodEnum;

interface GatewayServiceInterface
{
    public function charge(float $amount, TransactionPaymentMethodEnum $paymentMethod, int $recipientUserId): string;
}
