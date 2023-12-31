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
            'thumbnail' => ['small' => getAsset($this->thumbnail['sizes']['small']), 'medium' => getAsset($this->thumbnail['sizes']['medium']), 'large' => getAsset($this->thumbnail['sizes']['large'])],
            'title' => $this->title,
            'seo_title' => $this->seo_title,
            'description' => $this->description,
            'seo_description' => $this->seo_description,
            'unique_id' => $this->unique_id,
            'slug' => $this->slug,
            'body' => $this->body,
            'html_content' => $this->html_content,
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