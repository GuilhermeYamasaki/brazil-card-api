<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum TransactionPaymentMethodEnum: int
{
    use EnumTrait;

    case PIX = 1;
    case CREDIT_CARD = 2;
    case BOLETO = 3;
    case UNDEFINED = 4;
    case DEBIT_CARD = 5;

    public function asaas()
    {
        return match ($this) {
            self::PIX => 'PIX',
            self::CREDIT_CARD => 'CREDIT_CARD',
            self::BOLETO => 'BOLETO',
            self::UNDEFINED => 'UNDEFINED',
            self::DEBIT_CARD => 'DEBIT_CARD',
        };
    }

    public static function hydrateAsaas(string $asaasPaymentMethod): self
    {
        return match ($asaasPaymentMethod) {
            'PIX' => self::PIX,
            'CREDIT_CARD' => self::CREDIT_CARD,
            'BOLETO' => self::BOLETO,
            'UNDEFINED' => self::UNDEFINED,
            'DEBIT_CARD' => self::DEBIT_CARD,
        };
    }
}
