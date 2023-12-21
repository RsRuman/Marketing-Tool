<?php

namespace Moveon\Platform\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Moveon\Platform\Models\Platform;
use Spatie\Permission\Models\Role;

class PlatformRepository
{
    public function findByEmail($email): Model|Builder|null
    {
        return Platform::query()
            ->where('type', Platform::SHOPIFY)
            ->where('email', $email)
            ->first();
    }

    public function createUser($data)
    {
        return User::create($data);
    }

    public function createUserRole($data)
    {
        return Role::query()->create($data);
    }

    public function create($data) {
        return Platform::create($data);
    }
}
