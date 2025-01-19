<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'nickname' => $this->faker->name,
            'email' => $this->faker->email,
            'value' => $this->faker->randomFloat(2, 10, 1000),
            'message' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'payment_method' => $this->faker->randomElement(['manual', 'mercadopago']),
            'external_reference' => bin2hex(random_bytes(16))
        ];
    }
} 