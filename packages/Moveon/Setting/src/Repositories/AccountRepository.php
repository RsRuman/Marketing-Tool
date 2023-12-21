<?php

namespace Moveon\Setting\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Moveon\Platform\Models\Platform;

class AccountRepository
{
    public function accountDetail(): Model|null
    {
        $originId = Auth::user()->origin->id;

        return Platform::query()->where('user_id', $originId)->first();
    }

    public function updateAccount($platform, $data)
    {
        return $platform->update($data);
    }
}
