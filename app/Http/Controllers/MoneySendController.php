<?php

namespace App\Http\Controllers;

use App\Enums\TransactionPaymentMethodEnum;
use App\Http\Requests\MoneySendRequest;
use App\Services\Interfaces\AsaasServiceInterface;
use App\Services\Interfaces\TransactionServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class MoneySendController extends Controller
{
    public function __construct(
        private readonly TransactionServiceInterface $transactionService,
        private readonly AsaasServiceInterface $gatewayService,
    ) {
    }

    public function __invoke(MoneySendRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $senderUserId = auth()->user()->id;
            $recipientUserId = data_get($data, 'recipientUserId');
            $amount = data_get($data, 'amount');
            $paymentMethod = TransactionPaymentMethodEnum::tryFrom(
                data_get($data, 'paymentMethod'),
            );

            $gateway = 'asaas';
            $transactionId = $this->gatewayService->charge($amount, $paymentMethod, $recipientUserId);

            $historyId = $this->transactionService->saveHistory(
                $senderUserId,
                $transactionId,
                $amount,
                $paymentMethod,
                $gateway,
                $recipientUserId
            );

            return response()->json([
                'history_id' => $historyId,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error(self::class, [
                'request' => $request->all(),
                'exception' => $e,
            ]);

            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
