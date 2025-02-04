<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::query();

        // Aplicar filtro de status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Aplicar pesquisa
        if ($request->has('search') && strlen($request->search) >= 3) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nickname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('external_reference', 'like', "%{$search}%");
            });
        }

        // Contagens para os filtros
        $counts = [
            'all' => Donation::count(),
            'pending' => Donation::where('status', 'pending')->count(),
            'approved' => Donation::where('status', 'approved')->count(),
        ];

        // Carregar projetos para o modal de nova doação
        $projects = Project::all();

        $donations = $query->latest()->paginate(10);

        return view('admin.donations.index', compact('donations', 'counts', 'projects'));
    }

    public function create()
    {
        view('admin.donations.create');
    }

    public function edit(Donation $donation)
    {
        $projects = Project::all();
        return view('admin.donations.edit', compact('donation', 'projects'));
    }

    public function update(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'status' => 'sometimes|required|in:pending,approved,rejected',
            'external_reference' => 'sometimes|required|string',
            'nickname' => 'sometimes|required|string',
            'email' => 'sometimes|required|email',
            'message' => 'sometimes|nullable|string',
            'value' => 'sometimes|required|numeric',
            'phone' => 'sometimes|nullable|string',
            'project_id' => 'sometimes|required|exists:projects,id',
            'message_hidden' => 'sometimes|boolean',
            'message_hidden_reason' => 'required_if:message_hidden,1|nullable|string'
        ]);

        // Tratar o checkbox message_hidden
        if ($request->has('message_hidden')) {
            $validated['message_hidden'] = true;
            // Garantir que o motivo está presente quando a mensagem está oculta
            if (empty($validated['message_hidden_reason'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['message_hidden_reason' => 'O motivo da ocultação é obrigatório.']);
            }
        } else {
            $validated['message_hidden'] = false;
            $validated['message_hidden_reason'] = null;
        }

        // Guardar o status anterior
        $oldStatus = $donation->status;

        // Atualizar a doação
        $donation->update($validated);

        // Verificar se houve mudança específica de status
        if (isset($validated['status']) && $oldStatus !== $validated['status']) {
            $message = match($validated['status']) {
                'approved' => 'Doação aprovada com sucesso!',
                'rejected' => 'Doação rejeitada com sucesso!',
                default => 'Status da doação atualizado com sucesso!'
            };
        } else {
            $message = 'Doação atualizada com sucesso!';
        }

        // Se veio da página de edição, redirecionar de volta
        if ($request->is('*/edit')) {
            return redirect()->back()->with('success', $message);
        }

        // Caso contrário, redirecionar para o index
        return redirect()->route('admin.donations.index')->with('success', $message);
    }

    public function destroy(Donation $donation)
    {
        $donation->delete();

        return redirect()->route('admin.donations.index')->with('success', 'Doação excluída com sucesso.');
    }

    public function show(Donation $donation)
    {
        // Verificar se existe arquivo de comprovante
        $proofFileUrl = null;
        $proofFileExists = false;

        try {
            if (!empty($donation->proof_file)) {
                if (Storage::disk('public')->exists($donation->proof_file)) {
                    $proofFileUrl = Storage::url($donation->proof_file);
                    $proofFileExists = true;
                    Log::info('Arquivo encontrado: ' . $proofFileUrl);
                } else {
                    Log::warning('Arquivo não encontrado no storage: ' . $donation->proof_file);
                }
            } else {
                Log::info('Doação sem arquivo de comprovante (proof_file é null)');
            }
        } catch (Throwable $e) {
            Log::error('Erro ao verificar arquivo: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }

        return view('admin.donations.show', [
            'donation' => $donation,
            'proofFileUrl' => $proofFileUrl,
            'proofFileExists' => $proofFileExists
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'nickname' => 'required|string|min:2|max:255',
            'email' => 'required|email',
            'value' => 'required',
            'message' => 'nullable|string|max:1000',
        ]);

        // Converter valor para formato correto
        $value = str_replace(['.', ','], ['', '.'], $validated['value']);
        $value = (float) $value;

        try {
            $donation = Donation::create([
                'project_id' => $validated['project_id'],
                'nickname' => $validated['nickname'],
                'email' => $validated['email'],
                'message' => $validated['message'],
                'value' => $value,
                'status' => 'pending',
                'payment_method' => 'manual',
                'external_reference' => bin2hex(random_bytes(16))
            ]);

            // Se a requisição espera JSON (AJAX)
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Doação criada com sucesso!',
                    'donation' => $donation
                ]);
            }

            // Se for uma requisição normal
            return redirect()
                ->route('admin.donations.index')
                ->with('success', 'Doação criada com sucesso!');

        } catch (Throwable $e) {
            // Se a requisição espera JSON (AJAX)
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erro ao criar doação: ' . $e->getMessage()
                ], 500);
            }

            // Se for uma requisição normal
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar doação: ' . $e->getMessage());
        }
    }
}
