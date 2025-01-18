<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\Response;

class ProofController extends Controller
{
    private function debugStoragePaths(string $filename): void
    {
        $cleanFilename = basename(str_replace('proofs/', '', $filename));
        $paths = [
            'storage_path' => storage_path(),
            'app_path' => storage_path('app'),
            'public_path' => storage_path('app/public'),
            'proofs_path' => storage_path('app/public/proofs'),
            'disk_root' => config('filesystems.disks.proofs.root'),
            'file_path' => Storage::disk('proofs')->path($cleanFilename)
        ];

        foreach ($paths as $key => $path) {
            \Log::info("Path check [{$key}]:", [
                'path' => $path,
                'exists' => file_exists($path),
                'is_dir' => is_dir($path),
                'is_readable' => is_readable($path)
            ]);
        }
    }

    public function show(string $filename): StreamedResponse|Response|BinaryFileResponse
    {
        try {
            $this->debugStoragePaths($filename);

            // Limpa o nome do arquivo
            $cleanFilename = basename(str_replace('proofs/', '', $filename));
            
            // Busca a doação
            $donation = \App\Models\Donation::where('proof_file', 'proofs/' . $cleanFilename)
                ->first();
            
            if (!$donation) {
                \Log::warning('Doação não encontrada:', ['filename' => $cleanFilename]);
                abort(404);
            }

            // Verifica token
            $token = request()->query('access_token');
            $expectedToken = Cache::get('proof_access_token_'.$donation->id);

            if (!$token || $token !== $expectedToken) {
                \Log::warning('Token inválido:', [
                    'filename' => $cleanFilename,
                    'donation_id' => $donation->id
                ]);
                abort(403);
            }

            // Verifica se o arquivo existe
            if (!Storage::disk('proofs')->exists($cleanFilename)) {
                \Log::error('Arquivo não encontrado:', ['filename' => $cleanFilename]);
                abort(404);
            }

            $filePath = Storage::disk('proofs')->path($cleanFilename);
            $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';

            // Renova o token
            Cache::put('proof_access_token_'.$donation->id, $token, now()->addMinutes(5));

            // Retorna o arquivo
            if (str_starts_with($mimeType, 'image/')) {
                return response()->file($filePath, [
                    'Content-Type' => $mimeType,
                    'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                    'X-Content-Type-Options' => 'nosniff',
                    'Content-Security-Policy' => "default-src 'self' 'unsafe-inline'",
                    'X-Frame-Options' => 'DENY',
                    'X-XSS-Protection' => '1; mode=block'
                ]);
            }

            return response()->download(
                $filePath,
                'comprovante_' . date('Y-m-d_His') . '.' . pathinfo($cleanFilename, PATHINFO_EXTENSION),
                [
                    'Content-Type' => $mimeType,
                    'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0'
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Erro ao processar arquivo:', [
                'error' => $e->getMessage(),
                'filename' => $filename ?? null
            ]);
            abort(500, 'Erro ao processar arquivo');
        }
    }
}
