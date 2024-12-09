<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Inserir as doações
        DB::table('projects')->insert([
            [
                'id' => 1,
                'name' => 'Donaco',
                'description' => 'Sistema de ordem de serviço.',
                'goal' => 10000.00,
                'created_at' => '2024-09-15 01:29:12',
                'updated_at' => '2024-09-15 02:53:12'
            ],
            [
                'id' => 2,
                'name' => 'Pix Do Veio',
                'description' => 'Projeto criado para receber  doações',
                'goal' => 10000.00,
                'created_at' => '2024-09-15 01:45:43',
                'updated_at' => '2024-09-15 01:45:43'
            ]
        ]);
    }
}
