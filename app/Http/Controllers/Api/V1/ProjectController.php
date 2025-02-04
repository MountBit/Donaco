<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectController extends Controller
{
    public function index(): ResourceCollection
    {
        $projects = Project::with('donations')->get();

        return ProjectResource::collection($projects);
    }

    public function show(Project $project): JsonResponse
    {
        $project->load('donations');

        return response()->json([
            'data' => [
                'project' => new ProjectResource($project)
            ]
        ]);
    }
}
