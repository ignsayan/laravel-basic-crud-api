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
        return array_merge(
            parent::toArray($request),
            [
                'role' => $this->roles->pluck('name')->first(),
                'avatar' => $this->media->contains('collection_name', 'avatar') ? $this->getFirstMediaUrl('avatar') : null,
                'banner' => $this->media->contains('collection_name', 'banner') ? $this->getFirstMediaUrl('banner') : null,
            ]
        );
    }
}
