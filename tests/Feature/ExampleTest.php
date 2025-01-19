<?php

namespace Tests\Feature;

use App\Models\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        // Teste sem projetos
        $response = $this->get('/');
        $response->assertStatus(200);

        // Teste com um projeto
        Project::create([
            'name' => 'Projeto Teste',
            'description' => 'Descrição do projeto teste',
            'goal' => 1000.00,
            'is_active' => true
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
