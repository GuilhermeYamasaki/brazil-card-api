<?php

namespace App\Jobs;

use App\Enums\TransactionPaymentMethodEnum;
use App\Enums\TransactionStatusEnum;
use App\Mail\PaidEventMail;
use App\Mail\PendingEventMail;
use App\Mail\RefundEventMail;
use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class WebhookAsaasJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        private readonly array $data
    ) {
    }

    public function handle(
        TransactionRepositoryInterface $transactionRepository,
        UserRepositoryInterface $userRepository
    ): void {
        $this->userRepository = $userRepository;

        $newStatus = TransactionStatusEnum::hydrateAsaas(
            data_get($this->data, 'payment.status')
        );

        $newPaymentMethod = TransactionPaymentMethodEnum::hydrateAsaas(
            data_get($this->data, 'payment.billingType')
        );

        $history = $transactionRepository->findByTransaction(
            data_get($this->data, 'payment.id')
        );

        match ($newStatus) {
            TransactionStatusEnum::PAID => $this->paidEvent($history),
            TransactionStatusEnum::CONFIRMED => $this->paidEvent($history),
            TransactionStatusEnum::REFUND => $this->refundEvent($history),
            TransactionStatusEnum::PENDING => $this->pendingEvent($history),
            TransactionStatusEnum::OVERDUE => $this->pendingEvent($history),
        };

        $transactionRepository->update(
            data_get($this->data, 'payment.id'),
            [
                'status' => $newStatus->value,
                'payment_method' => $newPaymentMethod,
            ]
        );
    }

    private function paidEvent(array $history): void
    {
        $this->userRepository->addMoney(
            data_get($history, 'user_recipient_id'),
            data_get($history, 'amount')
        );

        $this->userRepository->subtractMoney(
            data_get($history, 'user_sender_id'),
            data_get($history, 'amount')
        );

        $sender = $this->userRepository->findById(
            data_get($history, 'user_sender_id')
        );

        $recipient = $this->userRepository->findById(
            data_get($history, 'user_recipient_id')
        );

        Mail::to($sender->email)
            ->send(new PaidEventMail([
                'senderName' => $sender->name,
                'recipientName' => $recipient->name,
                'amount' => data_get($history, 'amount'),
                'transactionReceiptUrl' => data_get($this->data, 'payment.transactionReceiptUrl'),
            ]));

    }

    private function refundEvent(array $history): void
    {
        $this->userRepository->addMoney(
            data_get($history, 'user_sender_id'),
            data_get($history, 'amount')
        );

        $this->userRepository->subtractMoney(
            data_get($history, 'user_recipient_id'),
            data_get($history, 'amount')
        );

        $sender = $this->userRepository->findById(
            data_get($history, 'user_sender_id')
        );

        $recipient = $this->userRepository->findById(
            data_get($history, 'user_recipient_id')
        );

        Mail::to($sender->email)
            ->send(new RefundEventMail([
                'senderName' => $sender->name,
                'recipientName' => $recipient->name,
                'amount' => data_get($history, 'amount'),
                'transactionReceiptUrl' => data_get($this->data, 'payment.transactionReceiptUrl'),
            ]));
    }

    private function pendingEvent(array $history): void
    {
        $sender = $this->userRepository->findById(
            data_get($history, 'user_sender_id')
        );

        $recipient = $this->userRepository->findById(
            data_get($history, 'user_recipient_id')
        );

        Mail::to($sender->email)
            ->send(new PendingEventMail([
                'senderName' => $sender->name,
                'recipientName' => $recipient->name,
                'amount' => data_get($history, 'amount'),
                'invoiceUrl' => data_get($this->data, 'payment.invoiceUrl'),
            ]));
    }
}
