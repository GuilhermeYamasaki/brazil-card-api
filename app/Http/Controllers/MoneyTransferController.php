<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoneyTransferRequest;
use App\Services\Interfaces\AsaasServiceInterface;
use App\Services\Interfaces\TransactionServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class MoneyTransferController extends Controller
{
    public function __construct(
        private readonly TransactionServiceInterface $transactionService,
        private readonly AsaasServiceInterface $gatewayService,
        private readonly UserServiceInterface $userService,
    ) {
    }

    public function __invoke(MoneyTransferRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $senderUserId = auth()->user()->id;
            $amount = data_get($data, 'amount');

            $userCanTransfer = $this->userService->canTransfer($senderUserId, $amount);
            if (! $userCanTransfer) {
                return response()->json([
                    'message' => 'Não tem saldo suficiente',
                ], Response::HTTP_FORBIDDEN);
            }

            $recipientUserId = data_get($data, 'recipientUserId');

            $gateway = 'asaas';
            $transactionId = $this->gatewayService->charge($amount, $senderUserId);

            $historyId = $this->transactionService->saveHistory(
                $senderUserId,
                $transactionId,
                $amount,
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
