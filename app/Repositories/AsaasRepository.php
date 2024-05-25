<?php

namespace App\Repositories;

use App\Repositories\Interfaces\AsaasRepositoryInterface;
use Illuminate\Support\Facades\Http;

class AsaasRepository implements AsaasRepositoryInterface
{
    protected $baseUri;

    protected $headers;

    public function __construct()
    {
        $this->baseUri = config('asaas.url');
        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'access_token' => config('asaas.token'),
        ];
    }

    public function findCustomerByEmail(string $email): ?array
    {
        $response = Http::withHeaders($this->headers)
            ->get("{$this->baseUri}/customers", [
                'email' => $email,
            ]);

        return collect($response->json()['data'])->first();
    }

    public function createCustomer(array $customerData): array
    {
        $response = Http::withHeaders($this->headers)
            ->post("{$this->baseUri}/customers", $customerData);

        return $response->json();

    }

    public function createTransaction(array $transactionData): array
    {
        $response = Http::withHeaders($this->headers)
            ->post("{$this->baseUri}/payments", $transactionData);

        return $response->json();
    }
}
