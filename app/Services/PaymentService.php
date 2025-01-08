<?php

namespace App\Services;

use App\Services\MercadoPagoService;
use Illuminate\Support\Facades\Storage;

class PaymentService
{
    protected MercadoPagoService $mercadoPagoService;

    public function __construct(MercadoPagoService $mercadoPagoService)
    {
        $this->mercadoPagoService = $mercadoPagoService;
    }

    public function processPayment(array $data): array
    {
        if ($data['payment_method'] === 'manual') {
            return $this->processManualPayment($data);
        }

        return $this->processMercadoPagoPayment($data);
    }

    protected function processManualPayment(array $data): array
    {
        $proofFile = null;
        if (isset($data['proof_file'])) {
            $proofFile = $data['proof_file']->store('proof_files', 'public');
        }

        $externalReference = bin2hex(random_bytes(16));

        return [
            'success' => true,
            'status' => 'pending',
            'proof_file' => $proofFile,
            'external_reference' => $externalReference
        ];
    }

    protected function processMercadoPagoPayment(array $data): array
    {
        $externalReference = bin2hex(random_bytes(16));

        return $this->mercadoPagoService->createPayment(
            $data['nickname'],
            $data['email'],
            $data['value'],
            $externalReference
        );
    }
} 