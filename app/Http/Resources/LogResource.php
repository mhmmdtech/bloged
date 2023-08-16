<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'actioner' => $this->whenLoaded('actioner'),
            'action' => $this->action,
            'model_type' => $this->model_type,
            'model_id' => $this->model_id,
            'old_model' => $this->old_model,
            'new_model' => $this->new_model,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}