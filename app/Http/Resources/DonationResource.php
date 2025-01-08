<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project' => new ProjectResource($this->whenLoaded('project')),
            'nickname' => $this->nickname,
            'email' => $this->email,
            'value' => $this->value,
            'message' => $this->when(!$this->message_hidden, $this->message),
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'created_at' => $this->created_at->toIso8601String()
        ];
    }
} 