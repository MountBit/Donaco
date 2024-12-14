<?php

namespace App\Repositories;

use App\Models\Donation;

class DonationRepository
{
    public function create(array $data)
    {
        \Log::info('DonationRepository: Criando doação com dados:', $data);
        
        try {
            $donation = Donation::create($data);
            \Log::info('DonationRepository: Doação criada:', $donation->toArray());
            return $donation;
        } catch (\Exception $e) {
            \Log::error('DonationRepository: Erro ao criar doação:', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function findByExternalReference($reference)
    {
        return Donation::where('external_reference', $reference)->first();
    }

    public function updateStatus($id, $status)
    {
        return Donation::where('id', $id)->update([
            'status' => $status,
            'updated_at' => now()
        ]);
    }

    public function getRecentApprovedDonations()
    {
        return Donation::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getRankingDonations($donations)
    {
        if (!$donations) {
            return [];
        }

        $donations = $donations->toArray();

        usort($donations, function ($a, $b) {
            // Ensure numeric comparison by converting to float
            $valueA = (float) preg_replace('/[^0-9.]/', '', $a['value']);
            $valueB = (float) preg_replace('/[^0-9.]/', '', $b['value']);
            return $valueB <=> $valueA; // Use spaceship operator for stable sorting
        });

        return array_slice($donations, 0, 5);
    }
}
