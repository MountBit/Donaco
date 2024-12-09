<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateProject($request);

        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'goal' => $validated['goal'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('projects.index')->with('success', __('projects.messages.created_success'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $this->validateProject($request);

        $project->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'goal' => $validated['goal'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('projects.index')->with('success', __('projects.messages.updated_success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', __('projects.messages.deleted_success'));
    }

    protected function validateProject(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'goal' => 'required|numeric|min:0|max:9999999.99',
            'is_active' => 'boolean'
        ]);
    }

}
