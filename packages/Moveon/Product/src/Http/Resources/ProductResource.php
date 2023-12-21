<?php

namespace Moveon\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->_id,
            'product_id'       => $this->product_id,
            'title'            => $this->title,
            'price_range'      => $this->price_range,
            'image_url'        => $this->image_url,
            'event_created_at' => $this->event_created_at,
        ];
    }
}
