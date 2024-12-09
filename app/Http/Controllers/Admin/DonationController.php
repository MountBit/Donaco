<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        $query = Donation::query();

        // Aplicar filtro de status se fornecido
        if ($request->filled('status') && in_array($request->status, ['pending', 'approved'])) {
            $query->where('status', $request->status);
        }

        // Aplicar termo de pesquisa se fornecido com proteção contra SQL injection
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nickname', 'like', DB::raw('?'))
                  ->orWhere('email', 'like', DB::raw('?'))
                  ->orWhere('phone', 'like', DB::raw('?'))
                  ->setBindings([
                      "%{$searchTerm}%",
                      "%{$searchTerm}%",
                      "%{$searchTerm}%"
                  ]);
            });
        }

        $donations = $query->latest()->paginate(10);
        
        // Contagem por status para os badges usando queries seguras
        $counts = [
            'all' => Donation::count(),
            'pending' => Donation::where('status', 'pending')->count(),
            'approved' => Donation::where('status', 'approved')->count(),
        ];

        return view('admin.donations.index', [
            'donations' => $donations,
            'counts' => $counts,
            'currentStatus' => $request->status,
            'searchTerm' => $request->search
        ]);
    }

    public function create(){
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
            'status' => 'sometimes|required|in:pending,approved',
            'external_reference' => 'sometimes|required|string',
            'nickname' => 'sometimes|required|string',
            'email' => 'sometimes|required|email',
            'message' => 'sometimes|nullable|string',
            'value' => 'sometimes|required|numeric',
            'phone' => 'sometimes|nullable|string',
            'project_id' => 'sometimes|required|exists:projects,id',
            'message_hidden' => 'sometimes|boolean',
            'message_hidden_reason' => 'required_if:message_hidden,1|string|nullable'
        ]);

        $donation->update($validated);

        if ($request->has('status')) {
            $message = $validated['status'] === 'approved' 
                ? 'Doação aprovada com sucesso!' 
                : 'Status da doação atualizado com sucesso!';
            
            return redirect()->back()->with('success', $message);
        }

        return redirect()->route('donations.index')->with('success', 'Doação atualizada com sucesso!');
    }

    public function destroy(Donation $donation)
    {
        $donation->delete();
        return redirect()->route('donations.index')->with('success', 'Doação excluída com sucesso.');
    }

    public function show(Donation $donation)
    {
        return view('admin.donations.show', compact('donation'));
    }
}
