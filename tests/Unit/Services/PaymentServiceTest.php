<?php

namespace Tests\Unit\Services;

use App\Services\MercadoPagoService;
use App\Services\PaymentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    private PaymentService $paymentService;
    private MercadoPagoService $mercadoPagoService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mercadoPagoService = $this->createMock(MercadoPagoService::class);
        $this->paymentService = new PaymentService($this->mercadoPagoService);
    }

    public function test_process_manual_payment(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('proof.pdf', 100);

        $data = [
            'payment_method' => 'manual',
            'proof_file' => $file
        ];

        $result = $this->paymentService->processPayment($data);

        $this->assertEquals('pending', $result['status']);
        $this->assertNotNull($result['proof_file']);
        $this->assertNotNull($result['external_reference']);
        
        Storage::disk('public')->assertExists($result['proof_file']);
    }

    public function test_process_mercadopago_payment(): void
    {
        $expectedResult = [
            'qr_code' => 'test-qr-code',
            'qr_code_base64' => 'test-base64',
            'external_reference' => 'test-reference'
        ];

        $this->mercadoPagoService
            ->expects($this->once())
            ->method('createPayment')
            ->willReturn($expectedResult);

        $data = [
            'payment_method' => 'mercadopago',
            'nickname' => 'Test User',
            'email' => 'test@example.com',
            'value' => 100.00
        ];

        $result = $this->paymentService->processPayment($data);

        $this->assertEquals($expectedResult, $result);
    }
} 