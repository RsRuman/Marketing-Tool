<?php

namespace Moveon\Segmentation\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Moveon\Segmentation\Models\Filter;
use Moveon\Segmentation\Models\FilterCriteria;

class FilterRepository
{
    public function all(): Collection|array
    {
        $originId = Auth::user()->origin->id;

        return Filter::query()
            ->where('status', Filter::STATUS_ACTIVE)
            ->where('user_id', $originId)
            ->orWhereNull('user_id')
            ->with(['filterCriterias' => function ($query) {
                $query->where('status', FilterCriteria::STATUS_ACTIVE);
            }])->get();
    }

    public function globalFilter(): Collection|array
    {
        return FilterCriteria::query()
            ->whereNull('filter_id')
            ->get();
    }
}
