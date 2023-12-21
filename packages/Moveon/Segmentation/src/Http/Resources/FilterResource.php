<?php

namespace Moveon\Segmentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilterResource extends JsonResource
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
            'id'        => $this->id,
            'key'       => $this->key,
            'label'     => $this->label,
            'type'      => $this->type,
            'criterias' => FilterCriteriaResource::collection($this->whenLoaded('filterCriterias'))
        ];
    }
}
