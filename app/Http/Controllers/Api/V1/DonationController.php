<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DonationRequest;
use App\Http\Resources\DonationResource;
use App\Services\DonationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DonationController extends Controller
{
    protected DonationService $donationService;

    public function __construct(DonationService $donationService)
    {
        $this->donationService = $donationService;
    }

    public function index(): ResourceCollection
    {
        $donations = $this->donationService->getAllDonations();

        return DonationResource::collection($donations);
    }

    public function store(DonationRequest $request): JsonResponse
    {
        $donation = $this->donationService->createDonation($request->validated());
        $donation->load('project');

        return response()->json([
            'data' => [
                'message' => 'Doação criada com sucesso',
                'donation' => new DonationResource($donation)
            ]
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $donation = $this->donationService->getDonation($id);

        return response()->json([
            'data' => [
                'donation' => new DonationResource($donation)
            ]
        ]);
    }
}
