<?php

namespace App\Http\Controllers;

use App\Http\Requests\WehbookAsaasRequest;
use App\Jobs\WebhookAsaasJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class WehbookAsaasController extends Controller
{
    public function __invoke(WehbookAsaasRequest $request): JsonResponse
    {
        $data = $request->all();

        WebhookAsaasJob::dispatch($data);

        return response()->json(status: Response::HTTP_OK);
    }
}
