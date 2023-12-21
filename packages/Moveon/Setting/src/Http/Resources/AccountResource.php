<?php

namespace Moveon\Setting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'company_name'  => $this->name,
            'platform_type' => $this->type,
            'email'         => $this->email,
            'store'         => $this->domain,
            'website'       => $this->website,
        ];
    }
}
