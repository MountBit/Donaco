<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'goal' => $this->goal,
            'total_donations' => $this->whenLoaded('donations', function() {
                return $this->donations->where('status', 'approved')->sum('value');
            }),
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toIso8601String()
        ];
    }
}
