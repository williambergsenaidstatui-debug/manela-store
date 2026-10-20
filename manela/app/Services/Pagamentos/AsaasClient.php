<?php

namespace App\Services\Pagamentos;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class AsaasClient
{
    public function createCustomer(array $customer): array
    {
        $payload = array_filter([
            'name' => $customer['name'],
            'cpfCnpj' => $customer['cpf_cnpj'],
            'email' => $customer['email'] ?? null,
            'phone' => $customer['phone'] ?? null,
        ], fn ($value) => filled($value));

        return $this->client()->post('/customers', $payload)->throw()->json();
    }

    public function createPayment(array $payment): array
    {
        return $this->client()
            ->post('/payments', [
                'customer' => $payment['customer'],
                'billingType' => $payment['billing_type'],
                'value' => $payment['value'],
                'dueDate' => $payment['due_date'],
                'description' => $payment['description'],
                'externalReference' => $payment['external_reference'],
            ])
            ->throw()
            ->json();
    }

    public function pixQrCode(string $paymentId): array
    {
        return $this->client()->get("/payments/{$paymentId}/pixQrCode")->throw()->json();
    }

    private function client(): PendingRequest
    {
        $accessToken = config('services.asaas.access_token');

        if (blank($accessToken)) {
            throw new RuntimeException('Configure ASAAS_ACCESS_TOKEN antes de criar pagamentos online.');
        }

        return Http::baseUrl($this->baseUrl())
            ->acceptJson()
            ->asJson()
            ->withHeaders([
                'access_token' => $accessToken,
                'User-Agent' => config('app.name', 'San Doces').'/1.0',
            ])
            ->connectTimeout((int) config('services.asaas.connect_timeout', 5))
            ->timeout((int) config('services.asaas.timeout', 15));
    }

    private function baseUrl(): string
    {
        return config('services.asaas.environment') === 'production'
            ? config('services.asaas.production_url')
            : config('services.asaas.sandbox_url');
    }
}
