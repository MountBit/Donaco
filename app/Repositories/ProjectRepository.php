<?php

namespace App\Repositories;

use App\Models\Project;

class ProjectRepository
{
    public function getAllProjects()
    {
        return Project::where('is_active', true)->get();
    }
}
