<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'thumbnail' => $this->thumbnail,
            'title' => $this->title,
            'seo_title' => $this->seo_title,
            'description' => $this->description,
            'seo_description' => $this->seo_description,
            'body' => $this->body,
            'is_featured' => $this->is_featured ? true : false,
            'author' => $this->whenLoaded('author'),
            'category' => $this->whenLoaded('category'),
            'status' => ['key' => $this->status->value, 'value' => $this->status->label()],
            'reading_time' => $this->reading_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}