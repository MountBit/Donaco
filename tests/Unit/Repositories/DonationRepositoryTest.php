<?php

namespace Tests\Unit\Repositories;

use App\Models\Donation;
use App\Models\Project;
use App\Repositories\DonationRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private DonationRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new DonationRepository();
    }

    public function test_get_all_approved_donations(): void
    {
        // Criar doações com diferentes status
        Donation::factory()->count(3)->create(['status' => 'approved']);
        Donation::factory()->count(2)->create(['status' => 'pending']);

        $donations = $this->repository->getAllApproved();

        $this->assertCount(3, $donations);
        $donations->each(function ($donation) {
            $this->assertEquals('approved', $donation->status);
        });
    }

    public function test_create_donation(): void
    {
        $project = Project::factory()->create();
        
        $data = [
            'project_id' => $project->id,
            'nickname' => 'Test Donor',
            'email' => 'test@example.com',
            'value' => 100.00,
            'status' => 'pending',
            'payment_method' => 'manual'
        ];

        $donation = $this->repository->create($data);

        $this->assertDatabaseHas('donations', [
            'id' => $donation->id,
            'nickname' => 'Test Donor'
        ]);
    }
}
