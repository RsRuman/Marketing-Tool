<?php

namespace Moveon\Product\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Moveon\Customer\Models\Product;

class ProductRepository
{
    public function all($request): LengthAwarePaginator
    {
        $originId = Auth::user()->origin->id;
        $perPage  = $request->input('per_page', 10);

        return Product::query()
            ->where('user_id', $originId)
            ->paginate($perPage);
    }

    public function find($id): Model|null
    {
        $originId = Auth::user()->origin->id;

        return Product::query()
            ->where('user_id', $originId)
            ->where('_id', $id)
            ->first();
    }
}
