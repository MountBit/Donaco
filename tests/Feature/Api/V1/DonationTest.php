<?php

namespace Tests\Feature\Api\V1;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DonationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Project $project;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->project = Project::factory()->create();
        $this->token = $this->user->createToken('api-token')->plainTextToken;
    }

    public function test_can_list_donations(): void
    {
        $response = $this->getJson('/api/v1/donations');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'project',
                        'nickname',
                        'email',
                        'value',
                        'status',
                        'payment_method',
                        'created_at'
                    ]
                ]
            ]);
    }

    public function test_can_create_manual_donation(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('proof.pdf', 100);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/v1/donations', [
                'project_id' => $this->project->id,
                'nickname' => 'Doador Teste',
                'email' => 'doador@example.com',
                'value' => 100.00,
                'message' => 'Teste de doação',
                'payment_method' => 'manual',
                'proof_file' => $file
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'message',
                    'donation' => [
                        'id',
                        'project',
                        'nickname',
                        'email',
                        'value',
                        'status',
                        'payment_method'
                    ]
                ]
            ]);

        Storage::disk('public')->assertExists('proof_files/' . $file->hashName());
    }

    public function test_cannot_create_donation_with_invalid_data(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/v1/donations', [
                'project_id' => 999,
                'nickname' => '',
                'email' => 'invalid-email',
                'value' => -100,
                'payment_method' => 'invalid'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'project_id',
                'nickname',
                'email',
                'value',
                'payment_method'
            ]);
    }
}
