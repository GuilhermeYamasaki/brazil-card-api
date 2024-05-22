<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Services\GenerateTokenService;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(
        private readonly GenerateTokenService $generateTokenService
    ) {
    }

    public function __invoke(AuthRequest $authRequest)
    {
        $credentials = $authRequest->validated();

        if (! auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $client = auth()->user();

        return response()->json([
            'token' => $this->generateTokenService->handle($client),
        ]);
    }
}
