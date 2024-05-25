<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoneyChargeRequest;
use App\Services\Interfaces\AsaasServiceInterface;
use App\Services\Interfaces\TransactionServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class MoneyChargeController extends Controller
{
    public function __construct(
        private readonly TransactionServiceInterface $transactionService,
        private readonly AsaasServiceInterface $gatewayService,
    ) {
    }

    public function __invoke(MoneyChargeRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $recipientUserId = auth()->user()->id;
            $senderUserId = data_get($data, 'senderUserId');
            $amount = data_get($data, 'amount');

            $gateway = 'asaas';
            $transactionId = $this->gatewayService->charge($amount, $senderUserId);

            $historyId = $this->transactionService->saveHistory(
                $senderUserId,
                $transactionId,
                $amount,
                $gateway,
                $recipientUserId
            );

            //Enviar email para senderUserId, link do pagamento do asaas

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
