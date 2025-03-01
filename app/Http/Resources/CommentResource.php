<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', fn () => UserResource::make($this->user)),
            'listing' => $this->whenLoaded('listing', fn () => ListingResource::make($this->product)),
            'body' => $this->body,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'can' => [
                'delete' => $request->user()?->can('delete', $this->resource),
                'update' => $request->user()?->can('update', $this->resource),
            ]
        ];
    }
}