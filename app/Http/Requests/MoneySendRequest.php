<?php

namespace App\Http\Requests;

use App\Enums\TransactionPaymentMethodEnum;
use Illuminate\Foundation\Http\FormRequest;

class MoneySendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'recipientUserId' => 'required|integer|exists:users,id',
            'paymentMethod' => 'required|integer|in:'.TransactionPaymentMethodEnum::values()->implode(','),
            'amount' => 'required|numeric|min:0',
        ];
    }
}
