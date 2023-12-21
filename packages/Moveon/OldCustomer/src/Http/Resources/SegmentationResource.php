<?php

namespace Moveon\Customer\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SegmentationResource extends JsonResource
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
            'name'     => $this->name,
            'label'    => $this->label,
            'criteria' => new SegmentationCriteriaResource($this->whenLoaded('segmentationCriteria'))
        ];
    }
}
