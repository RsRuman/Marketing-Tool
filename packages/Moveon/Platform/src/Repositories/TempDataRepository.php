<?php


namespace Moveon\Platform\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Moveon\User\Models\TempData;

class TempDataRepository
{
    public function create($data)
    {
        return TempData::create($data);
    }

    public function findByUserId($userId): Model|Builder|null
    {
        return TempData::query()->where('user_id', $userId)->first();
    }
}
