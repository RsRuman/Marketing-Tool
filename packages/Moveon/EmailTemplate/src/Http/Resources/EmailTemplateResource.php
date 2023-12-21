<?php

namespace Moveon\EmailTemplate\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Moveon\User\Http\Resources\UserResource;

class EmailTemplateResource extends JsonResource
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
                'design' => $this->design,
                'html'   => $this->html,
                'status' => $this->status,
                'created_by' => new UserResource($this->whenLoaded('createdBy')),
                'updated_by' => new UserResource($this->whenLoaded('updatedBy')),
            ];
    }
}
