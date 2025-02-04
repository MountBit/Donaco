<?php

namespace Tests\Feature\Api\V1;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('api-token')->plainTextToken;
    }

    public function test_can_list_projects(): void
    {
        Project::factory()->count(3)->create();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/v1/projects');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'goal',
                        'is_active',
                        'created_at'
                    ]
                ]
            ]);
    }

    public function test_can_show_project_details(): void
    {
        $project = Project::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("/api/v1/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'project' => [
                        'id',
                        'name',
                        'description',
                        'goal',
                        'total_donations',
                        'is_active',
                        'created_at'
                    ]
                ]
            ]);
    }
}
