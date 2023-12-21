<?php

namespace Moveon\Setting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IntegrationShortResource extends JsonResource
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
            'slug'         => $this->slug,
            'type'         => $this->type,
            'logo'         => asset('storage/' . $this->logo)
        ];
    }
}
