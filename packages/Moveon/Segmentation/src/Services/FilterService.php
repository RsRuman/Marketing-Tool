<?php

namespace Moveon\Segmentation\Services;

use Illuminate\Database\Eloquent\Collection;
use Moveon\Segmentation\Repositories\FilterRepository;

class FilterService
{
    private FilterRepository $filterRepository;

    public function __construct(FilterRepository $filterRepository)
    {
        $this->filterRepository = $filterRepository;
    }

    public function getFilters(): Collection|array
    {
        return $this->filterRepository->all();
    }

    /**
     * Get global filters
     * if filter type unknown for us
     * @return Collection|array
     */
    public function getGlobalFilters(): Collection|array
    {
        return $this->filterRepository->globalFilter();
    }
}
