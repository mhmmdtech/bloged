<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'national_code' => $this->national_code,
            'mobile_number' => $this->mobile_number,
            'gender' => ['key' => $this->gender->value, 'value' => $this->gender->label()],
            'email' => $this->email,
            'username' => $this->username,
            'creator' => $this->whenLoaded('creator'),
            'avatar' => $this->avatar,
            'birthday' => $this->birthday,
            'military_status' => $this->military_status ? ['key' => $this->military_status->value, 'value' => $this->military_status->label()] : $this->military_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}