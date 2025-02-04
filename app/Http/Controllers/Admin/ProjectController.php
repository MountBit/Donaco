<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DonationHelper;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view('admin.projects.index', compact('projects'));
    }

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
                'goal' => DonationHelper::convertMoneyToCents($validated['goal']) / 100,
                'description' => $validated['description'],
                'is_active' => $request->has('is_active')
            ]);

            return redirect()
                ->route('admin.projects.index')
                ->with('success', __('projects.messages.created_success'));

        } catch (Throwable $e) {
            Log::error('Erro ao criar projeto:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao criar projeto: ' . $e->getMessage()]);
        }
    }

    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

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
                'goal' => DonationHelper::convertMoneyToCents($validated['goal']) / 100,
                'description' => $validated['description'],
                'is_active' => (bool) $request->has('is_active')
            ]);

            return redirect()
                ->route('admin.projects.index')
                ->with('success', __('projects.messages.updated_success'));

        } catch (Throwable $e) {
            Log::error('Erro ao atualizar projeto:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao atualizar projeto: ' . $e->getMessage()]);
        }
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()
            ->route('admin.projects.index')
            ->with('success', __('projects.messages.deleted_success'));
    }
}
