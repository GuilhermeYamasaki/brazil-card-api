<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum TransactionPaymentMethodEnum: int
{
    use EnumTrait;

    case PIX = 1;
    case CREDIT_CARD = 2;

    public function asaas()
    {
        return match ($this) {
            self::PIX => 'PIX',
            self::CREDIT_CARD => 'CREDIT_CARD',
        };
    }
}
