<?php

namespace App\Services\Interfaces;

interface GatewayServiceInterface
{
    public function charge(float $amount, int $senderUserId): string;

    public function getName(): string;
}
