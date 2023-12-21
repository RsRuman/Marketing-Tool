<?php

namespace Moveon\Customer\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Moveon\User\Http\Resources\UserResource;

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
            'id'     => $this->id,
            'name'   => $this->name,
            'label'  => $this->label,
            'status' => $this->status,
            'group'  => new GroupResource($this->whenLoaded('group')),
            'filter_criterias' => FilterCriteriaResource::collection($this->whenLoaded('filterCriterias'))
        ];
    }
}
