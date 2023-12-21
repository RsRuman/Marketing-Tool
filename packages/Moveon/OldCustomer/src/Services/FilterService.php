<?php

namespace Moveon\Customer\Services;

use Illuminate\Database\Eloquent\Collection;
use Moveon\Customer\Repositories\FilterRepository;

class FilterService
{
    private FilterRepository $filterRepository;

    public function __construct(FilterRepository $filterRepository)
    {
        $this->filterRepository = $filterRepository;
    }

    public function getAllFilters(): Collection|array
    {
        return $this->filterRepository->all();
    }
}
