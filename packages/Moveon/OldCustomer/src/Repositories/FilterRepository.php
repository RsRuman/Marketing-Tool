<?php

namespace Moveon\Customer\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Moveon\Customer\Models\Filter;

class FilterRepository
{
    public function all(): Collection|array
    {
        return Filter::query()->with(['group', 'filterCriterias' => function($query) {
            $query->with(['group', 'childrens' => function($query) {
                $query->with('childrens');
            }]);
        }])->get();
    }
}
