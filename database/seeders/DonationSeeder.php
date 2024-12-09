<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        // Inserir as doações
        DB::table('donations')->insert([
            [
                'external_reference' => '$2y$10$FurmTwNN0/A3nlMm8ky6ne0OII5HXtTxyXlKizng7LY3gtJOPzjY6',
                'nickname' => 'Biu',
                'email' => 'biu@donaco.com.br',
                'message' => '',
                'status' => 'approved',
                'value' => 0.01,
                'created_at' => '2023-12-28 09:20:11',
                'updated_at' => '2024-01-14 05:46:44',
                'phone' => null,
                'project_id' => '1'
            ],
            [
                'external_reference' => '$2y$10$loi7Ss/l03t.fya2ypBmJuPHnE8g0oewmb8Eu8/bvDtwE2Gqvqjye',
                'nickname' => 'Ramon',
                'email' => 'ramon@donaco.com.br',
                'message' => 'Let\'s go',
                'status' => 'approved',
                'value' => 5,
                'created_at' => '2023-12-28 09:40:09',
                'updated_at' => '2024-01-14 05:55:29',
                'phone' => null,
                'project_id' => '1'
            ],
            [
                'external_reference' => '$2y$10$TWcbm9WGVyQ3Z8MfUVEfKO75jj0nzEDc.5APSUjkPbaL24chLCsPW',
                'nickname' => 'Santt',
                'email' => 'contato@veiodopix.com.br',
                'message' => 'Good job',
                'status' => 'approved',
                'value' => 1,
                'created_at' => '2023-12-30 15:37:10',
                'updated_at' => '2024-01-16 06:19:27',
                'phone' => null,
                'project_id' => '2'
            ],
            [
                'external_reference' => '$2y$10$fbEb11wo6mjxcCqFcI60Ou/u5Z/gcOios26mctZJg0o2nodzAVDjq',
                'nickname' => 'Lobo Juli',
                'email' => 'juliolobo@donaco.com.br',
                'message' => 'Não é muito mais sé de coração',
                'status' => 'approved',
                'value' => 20,
                'created_at' => '2024-01-01 11:18:07',
                'updated_at' => '2024-01-17 13:52:16',
                'phone' => null,
                'project_id' => '2'
            ],
            [
                'external_reference' => '$2y$10$g0SrVmjVUQfwDSpVPs6Uz.R3dyHMdIH4VuymR0EZsnjgU0avwpiTa',
                'nickname' => 'Lobo Juli',
                'email' => 'juliolobo@donaco.com.br',
                'message' => 'Toma mais essa',
                'status' => 'approved',
                'value' => 25,
                'created_at' => '2024-01-05 06:35:22',
                'updated_at' => '2024-01-20 17:01:34',
                'phone' => null,
                'project_id' => '1'
            ],
            [
                'external_reference' => 'k7j4vs31wf1uac7yrmaahzyw',
                'nickname' => 'User Teste',
                'email' => 'user.teste@donaco.com.br',
                'message' => 'Verificação de site',
                'status' => 'pending',
                'value' => 0.01,
                'created_at' => '2024-07-16 01:24:38',
                'updated_at' => '2024-07-16 01:24:38',
                'phone' => '558197853085',
                'project_id' => '1'
            ],
            [
                'external_reference' => 'l4u1zu1u1petybwe9f8f9j46',
                'nickname' => 'Felipe Silva',
                'email' => 'felipe.silva@donaco.com.br',
                'message' => 'Ótimo projeto',
                'status' => 'approved',
                'value' => 0.01,
                'created_at' => '2024-09-08 20:55:36',
                'updated_at' => '2024-09-08 19:59:09',
                'phone' => '558197853085',
                'project_id' => '1'
            ],
            [
                'external_reference' => 'ixkld0i6owjtgnwkuvx06edq',
                'nickname' => 'Gustavo Fraga',
                'email' => 'gustavo.fraga@donaco.com.br',
                'message' => 'Ótimo projeto',
                'status' => 'approved',
                'value' => 0.01,
                'created_at' => '2024-09-08 21:08:42',
                'updated_at' => '2024-09-08 20:09:48',
                'phone' => '558197853085',
                'project_id' => '2'
            ]
        ]);

    }
}
