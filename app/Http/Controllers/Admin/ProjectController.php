<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Converte valor monetário do formato brasileiro para centavos
     * Ex: "1.234,56" -> 123456
     */
    private function convertMoneyToCents(string $value): int
    {
        // Remove tudo exceto números
        $value = preg_replace('/[^\d]/', '', $value);

        // Se o valor tiver menos de 3 dígitos, completa com zeros à direita
        if (strlen($value) < 3) {
            $value = str_pad($value, 3, '0', STR_PAD_RIGHT);
        }

        return (int) $value;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:projects,name',
                'goal' => 'required|string',
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean'
            ]);

            $project = Project::create([
                'name' => $validated['name'],
                'goal' => $this->convertMoneyToCents($validated['goal']) / 100, // Converte centavos para decimal
                'description' => $validated['description'],
                'is_active' => $request->has('is_active')
            ]);

            return redirect()
                ->route('admin.projects.index')
                ->with('success', __('projects.messages.created_success'));

        } catch (\Exception $e) {
            \Log::error('Erro ao criar projeto:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao criar projeto: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:projects,name,' . $project->id,
                'goal' => 'required|string',
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean'
            ]);

            $project->update([
                'name' => $validated['name'],
                'goal' => $this->convertMoneyToCents($validated['goal']) / 100,
                'description' => $validated['description'],
                'is_active' => (bool) $request->has('is_active')
            ]);

            return redirect()
                ->route('admin.projects.index')
                ->with('success', __('projects.messages.updated_success'));

        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar projeto:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao atualizar projeto: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()
            ->route('admin.projects.index')
            ->with('success', __('projects.messages.deleted_success'));
    }
}
