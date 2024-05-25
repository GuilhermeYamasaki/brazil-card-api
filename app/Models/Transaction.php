<?php

namespace App\Models;

use App\Enums\TransactionPaymentMethodEnum;
use App\Enums\TransactionStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'transaction_id',
        'user_sender_id',
        'user_recipient_id',
        'amount',
        'payment_method',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'payment_method' => TransactionPaymentMethodEnum::class,
            'status' => TransactionStatusEnum::class,
        ];
    }
}
