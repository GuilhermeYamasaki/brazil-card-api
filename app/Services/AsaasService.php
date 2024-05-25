<?php

namespace App\Services;

use App\Enums\TransactionPaymentMethodEnum;
use App\Repositories\Interfaces\AsaasRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\AsaasServiceInterface;
use RuntimeException;

class AsaasService implements AsaasServiceInterface
{
    public function __construct(
        private readonly AsaasRepositoryInterface $asaasRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly TransactionRepositoryInterface $userTransactionRepository
    ) {
    }

    public function charge(float $amount, TransactionPaymentMethodEnum $paymentMethod, int $recipientUserId): string
    {
        $recipientUser = $this->userRepository->findById($recipientUserId);
        $customer = $this->asaasRepository->findCustomerByEmail($recipientUser->email);
        $dueDate = now()->addDay()->format('Y-m-d');

        if (empty($customer)) {
            $customer = $this->asaasRepository->createCustomer([
                'name' => $recipientUser->name,
                'email' => $recipientUser->email,
                'cpfCnpj' => $recipientUser->document,
            ]);
        }

        $transaction = $this->asaasRepository->createTransaction([
            'customer' => data_get($customer, 'id'),
            'billingType' => $paymentMethod->asaas(),
            'value' => $amount,
            'description' => 'Cobrança gerada',
            'externalReference' => $recipientUser->id,
            'dueDate' => $dueDate,
        ]);

        $transactionId = data_get($transaction, 'id');

        if (empty($transactionId)) {
            throw new RuntimeException('Transação não criada');
        }

        return $transactionId;
    }
}
