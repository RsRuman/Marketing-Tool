<?php

namespace Moveon\Customer\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Moveon\Customer\Models\Customer;
use Moveon\Setting\Models\Lead;

class CustomerRepository
{
    public function all($request): LengthAwarePaginator
    {
        $perPage = $request->input('per_page', 10);

        return Lead::query()->with('tags')->paginate($perPage);
    }

    public function create($data) {
        return Customer::create($data);
    }

    public function find($id): Model|null
    {
        return Lead::query()->where('_id', $id)->first();
    }
}
