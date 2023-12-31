<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'thumbnail' => ['small' => getAsset($this->thumbnail['sizes']['small']), 'medium' => getAsset($this->thumbnail['sizes']['medium']), 'large' => getAsset($this->thumbnail['sizes']['large'])],
            'title' => $this->title,
            'seo_title' => $this->seo_title,
            'description' => $this->description,
            'seo_description' => $this->seo_description,
            'unique_id' => $this->unique_id,
            'slug' => $this->slug,
            'creator' => $this->whenLoaded('creator'),
            'status' => ['key' => $this->status->value, 'value' => $this->status->label()],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}