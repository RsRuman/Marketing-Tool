<?php

namespace Moveon\Setting\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Moveon\Customer\Models\Product;
use Moveon\Setting\Models\Event;
use Moveon\Setting\Models\Lead;

class EventRepository
{
    public function find($xSecreteToken): Model|null
    {
        return User::query()
            ->where('token', $xSecreteToken)
            ->first();
    }

    public function store($type, $data) {
        $originId = Auth::user()->origin->id;
        $data['user_id'] = $originId;

        if ($type === Event::TYPE_PRODUCTS) {
            return Product::create($data);
        }

        if ($type === Event::TYPE_CUSTOMERS) {
            return Lead::create($data);
        }

        $updatableData = $data;
        $updatableData['type'] = $type;

        return Event::create($updatableData);
    }
}
