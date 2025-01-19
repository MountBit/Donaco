<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SecureProofAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return response()->view('errors.proof-access', [], 403);
        }

        $user = Auth::user();
        $filename = $request->route('filename');
        $filename = str_replace('proofs/', '', $filename);
        $donationId = $this->getDonationIdFromFilename($filename);

        if (!$donationId) {
            \Log::warning('Doação não encontrada:', ['filename' => $filename]);
            return response()->view('errors.proof-access', [
                'message' => __('errors.404.message')
            ], 404);
        }

        // Verifica se o usuário tem permissão para acessar este arquivo
        if (!$this->canAccessFile($user, $filename)) {
            \Log::warning('Tentativa não autorizada de acesso a arquivo:', [
                'user_id' => $user->id,
                'file' => $filename,
                'ip' => $request->ip()
            ]);
            return response()->view('errors.proof-access', [
                'message' => __('errors.403.message')
            ], 403);
        }

        return $next($request);
    }

    protected function getDonationIdFromFilename($filename)
    {
        // Garante que temos o prefixo 'proofs/' para buscar no banco
        $dbFilename = !str_starts_with($filename, 'proofs/') ? 'proofs/' . $filename : $filename;
        return \App\Models\Donation::where('proof_file', $dbFilename)
            ->value('id');
    }

    protected function canAccessFile($user, $filename)
    {
        // Garante que temos o prefixo 'proofs/' para buscar no banco
        $dbFilename = !str_starts_with($filename, 'proofs/') ? 'proofs/' . $filename : $filename;
        return \App\Models\Donation::where('proof_file', $dbFilename)
            ->exists();
    }
}
