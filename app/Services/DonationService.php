<?php

namespace App\Services;

use App\Repositories\DonationRepository;
use App\Services\PaymentService;
use Illuminate\Support\Collection;

class DonationService
{
    protected DonationRepository $donationRepository;
    protected PaymentService $paymentService;

    public function __construct(
        DonationRepository $donationRepository,
        PaymentService $paymentService
    ) {
        $this->donationRepository = $donationRepository;
        $this->paymentService = $paymentService;
    }

    public function getAllDonations(): Collection
    {
        return $this->donationRepository->getAllApproved();
    }

    public function createDonation(array $data): mixed
    {
        // Processa o pagamento
        $paymentData = $this->paymentService->processPayment($data);
        
        // Cria a doação
        return $this->donationRepository->create(array_merge(
            $data,
            ['payment_data' => $paymentData]
        ));
    }

    public function getDonation(string $id): mixed
    {
        return $this->donationRepository->findOrFail($id);
    }
}
