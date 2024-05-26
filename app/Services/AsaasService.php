<?php

namespace App\Services;

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

    public function charge(float $amount, int $senderUserId): string
    {
        $senderUser = $this->userRepository->findById($senderUserId);
        $customer = $this->asaasRepository->findCustomerByEmail($senderUser->email);
        $dueDate = now()->addDay()->format('Y-m-d');

        if (empty($customer)) {
            $customer = $this->asaasRepository->createCustomer([
                'name' => $senderUser->name,
                'email' => $senderUser->email,
                'cpfCnpj' => $senderUser->document,
            ]);
        }

        $transaction = $this->asaasRepository->createTransaction([
            'customer' => data_get($customer, 'id'),
            'billingType' => 'UNDEFINED',
            'value' => $amount,
            'description' => 'Cobrança gerada',
            'dueDate' => $dueDate,
        ]);

        $transactionId = data_get($transaction, 'id');

        if (empty($transactionId)) {
            throw new RuntimeException('Transação não criada');
        }

        return $transactionId;
    }
}
