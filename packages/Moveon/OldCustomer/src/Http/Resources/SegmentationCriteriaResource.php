<?php

namespace Moveon\Customer\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SegmentationCriteriaResource extends JsonResource
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
            'name'       => $this->name,
            'value'      => $this->value,
            'value_type' => $this->value_type,
            'label'      => $this->label,
            'children'  => new SegmentationCriteriaResource($this->whenLoaded('children'))
        ];
    }
}
