<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Inserir o usuário
        User::create([
            'name' => 'Donaco',
            'email' => 'admin@donaco.com.br',
            'password' => Hash::make('donaco123'),
        ]);

        $this->call([
            ProjectSeeder::class,
            DonationSeeder::class
        ]);
    }
}
