<?php

namespace Moveon\Lead\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Moveon\Lead\Repositories\LeadRepository;

class LeadService
{
    private LeadRepository $leadRepository;

    public function __construct(LeadRepository $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }

    /**
     * Get all leads
     * @param $request
     * @return LengthAwarePaginator
     */
    public function getLeads($request): LengthAwarePaginator
    {
        return $this->leadRepository->all($request);
    }

    /**
     * Get lead
     * @param $id
     * @return Model|null
     */
    public function getLead($id): ?Model
    {
        return $this->leadRepository->find($id);
    }
}
