<?php

namespace Moveon\Setting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IntegrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'slug'         => $this->slug,
            'type'         => $this->type,
            'logo'         => asset('storage/' . $this->logo),
            'details'      => $this->details,
            'instructions' => $this->instructions,
            'status'       => $this->status
        ];
    }
}
