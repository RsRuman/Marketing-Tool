<?php

namespace Moveon\Lead\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Moveon\Tag\Http\Resources\TagResource;
use Moveon\User\Http\Resources\UserResource;

class LeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}
