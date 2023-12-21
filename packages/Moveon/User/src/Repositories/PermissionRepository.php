<?php

namespace Moveon\User\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;

class PermissionRepository
{
    public function all(): Collection
    {
        return Permission::all();
    }

}
