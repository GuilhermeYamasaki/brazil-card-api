<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use App\Services\GenerateTokenService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        private readonly GenerateTokenService $generateTokenService
    ) {
    }

    public function __invoke(AuthRequest $authRequest)
    {
        $credentials = $authRequest->validated();

        $user = User::query()
            ->whereEmail(data_get($credentials, 'email'))
            ->first();

        if (blank($user) || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(
                status: Response::HTTP_UNAUTHORIZED
            );
        }

        return response()->json([
            'token' => $this->generateTokenService->handle($user),
        ], Response::HTTP_OK);
    }
}
