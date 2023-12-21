<?php

namespace Moveon\Customer\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Moveon\Tag\Http\Resources\TagResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->_id,
            'customer_id'      => $this->customer_id,
            'first_name'       => $this->first_name,
            'last_name'        => $this->last_name,
            'name'             => $this->name,
            'gender'           => $this->gender,
            'phone'            => $this->phone,
            'post_code'        => $this->post_code,
            'event_created_at' => $this->event_created_at,
            'tags'             => TagResource::collection($this->whenLoaded('tags'))
        ];
    }
}
