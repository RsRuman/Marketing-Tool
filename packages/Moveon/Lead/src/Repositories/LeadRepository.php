<?php

namespace Moveon\Lead\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Moveon\Setting\Models\Lead;

class LeadRepository
{
    public function all($request): LengthAwarePaginator
    {
        $perPage  = $request->input('per_page', 10);
        $originId = Auth::user()->origin->id;

        return Lead::query()
            ->where('user_id', $originId)
            ->with('tags')
            ->paginate($perPage);
    }

    public function find($id): Model|null
    {
        $originId = Auth::user()->origin->id;

        return Lead::query()
            ->where('user_id', $originId)
            ->where('_id', $id)
            ->first();
    }
}
