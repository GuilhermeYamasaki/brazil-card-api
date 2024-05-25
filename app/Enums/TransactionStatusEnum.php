<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum TransactionStatusEnum: int
{
    use EnumTrait;

    case PENDING = 1;
    case PAID = 2;
    case REFUND = 3;
    case CANCELED = 4;
}
