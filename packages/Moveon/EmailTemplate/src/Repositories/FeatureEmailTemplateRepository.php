<?php

namespace Moveon\EmailTemplate\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Moveon\EmailTemplate\Models\FeatureEmailTemplate;

class FeatureEmailTemplateRepository
{
    public function all($request): LengthAwarePaginator
    {
        $perPage = $request->input('per_page', 10);
        return FeatureEmailTemplate::query()
            ->when($request->input('name'), function ($query) use($request) {
                $query->where('name', 'like', '%'. $request->input('name') .'%');
            })
            ->when($request->input('sort_by'), function ($query) use($request) {
                if ($request->input('sort_by') === 'name') {
                    $query->orderBy('name', 'ASC');
                }

                if ($request->input('sort_by') === 'date') {
                    $query->orderBy('created_at', 'ASC');
                }
            })
            ->paginate($perPage);
    }

    public function find($id): object|null
    {
        return FeatureEmailTemplate::query()
            ->where('id', $id)
            ->first();
    }
}
