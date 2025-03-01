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
            'builder' => $this->builder,
            'name' => $this->name,
            'link' => $this->link,
            'description' => $this->description,
            'image' => 'https://fls-9e511cc4-73e8-4419-b3b4-50d0f2a13cbe.laravel.cloud/' . $this->image,
            'comments_count' => $this->whenCounted('comments'),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'routes' => [
                'show' => $this->showRoute(),
            ],
        ];
    }
}
