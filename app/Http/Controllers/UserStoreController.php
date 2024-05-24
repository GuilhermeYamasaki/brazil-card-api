<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Services\Interfaces\UserServiceInterface;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UserStoreController extends Controller
{
    public function __construct(
        private readonly UserServiceInterface $userService
    ) {
    }

    public function __invoke(UserStoreRequest $request)
    {
        try {
            $data = $request->validated();
            $user = $this->userService->register($data);

            return response()->json([
                'token' => data_get($user, 'token'),
            ], Response::HTTP_CREATED);

        } catch (Exception $e) {

            Log::error(null, [
                'exception' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
