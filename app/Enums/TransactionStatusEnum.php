<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum TransactionStatusEnum: int
{
    use EnumTrait;

    case PENDING = 1;
    case PAID = 2;
    case REFUND = 3;
    case CONFIRMED = 4;
    case OVERDUE = 5;

    public static function hydrateAsaas(string $asaasStatus): self
    {
        return match ($asaasStatus) {
            'PENDING' => self::PENDING,
            'RECEIVED' => self::PAID,
            'REFUNDED' => self::REFUND,
            'CONFIRMED' => self::CONFIRMED,
            'OVERDUE' => self::OVERDUE
        };
    }
}
