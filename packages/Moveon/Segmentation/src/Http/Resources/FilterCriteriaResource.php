<?php

namespace Moveon\Segmentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilterCriteriaResource extends JsonResource
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
            'id'         => $this->id,
            'is_parent'  => $this->is_parent,
            'key'        => $this->key,
            'label'      => $this->label,
            'value'      => $this->value,
            'value_type' => $this->value_type,
        ];
    }
}
