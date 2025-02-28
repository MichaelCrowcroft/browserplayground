<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'link' => $this->link,
            'description' => $this->description,
            'user' => UserResource::make($this->whenLoaded('user')),
            'routes' => [
                'show' => $this->showRoute(),
            ],
        ];
    }
}
