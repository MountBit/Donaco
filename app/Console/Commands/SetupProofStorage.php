<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupProofStorage extends Command
{
    protected $signature = 'proofs:setup';
    protected $description = 'Configura a estrutura de armazenamento dos comprovantes';

    public function handle()
    {
        $this->info('Iniciando configuração do armazenamento de comprovantes...');

        $proofsPath = storage_path('app/public/proofs');
        $oldProofsPath = storage_path('app/private/proofs');

        // Cria o diretório se não existir
        if (!file_exists($proofsPath)) {
            mkdir($proofsPath, 0700, true);
            $this->info("Diretório proofs criado: {$proofsPath}");
        }

        // Move arquivos da pasta antiga se existirem
        if (file_exists($oldProofsPath)) {
            $this->info('Encontrada pasta antiga de comprovantes. Movendo arquivos...');
            $files = glob($oldProofsPath . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    $filename = basename($file);
                    rename($file, $proofsPath . '/' . $filename);
                    $this->line("Arquivo movido: {$filename}");
                }
            }
            rmdir($oldProofsPath);
            $this->info('Pasta antiga removida');
        }

        // Cria/atualiza .gitignore
        $gitignore = $proofsPath . '/.gitignore';
        file_put_contents($gitignore, "*\n!.gitignore\n");
        chmod($gitignore, 0600);

        // Remove link simbólico se existir
        $publicLink = public_path('storage/proofs');
        if (file_exists($publicLink)) {
            if (is_link($publicLink)) {
                unlink($publicLink);
                $this->info('Link simbólico removido');
            }
        }

        // Ajusta permissões
        chmod($proofsPath, 0700);
        
        // Ajusta permissões dos arquivos existentes
        foreach (glob($proofsPath . '/*') as $file) {
            if (is_file($file)) {
                chmod($file, 0600);
            }
        }
        
        $this->info("\nConfiguração concluída!");
        $this->info('Diretório de comprovantes: ' . $proofsPath);
        $this->info('Permissões: 700 (diretório), 600 (arquivos)');
        $this->info('Link simbólico removido: ' . $publicLink);
    }
}
