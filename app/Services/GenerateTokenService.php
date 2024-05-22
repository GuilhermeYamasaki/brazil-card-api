<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;

class GenerateTokenService
{
    private const DEFAULT_EXPIRATION_MINUTES = 30;

    public function handle(Authenticatable $model): string
    {
        $table = $model->getTable();

        $model->tokens()->delete();

        $token = $model
            ->createToken(
                "{$table}_token",
                ['*'],
                $this->generateExpiresAt()
            )->plainTextToken;

        return $token;
    }

    public function generateExpiresAt(): Carbon
    {
        return now()->addMinutes(
            config('sanctum.expiration', self::DEFAULT_EXPIRATION_MINUTES)
        );
    }
}
