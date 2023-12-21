<?php

namespace Moveon\User\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RoleRepository
{
    public function all(): Collection|array
    {
        $originId = Auth::user()->origin ? Auth::user()->origin->id : Auth::user()->id;
        return Role::query()
            ->where('user_id', $originId)
            ->get();
    }

    public function create($data): Model|Builder
    {
        return Role::query()->create($data);
    }

    public function find($id): Model|Collection|Builder|array|null
    {
        return Role::query()->where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->first();
    }

    public function update($data, $id): bool
    {
        $role = Role::findById($id);
        return $role->update($data);
    }

    public function delete($role) {
        return $role->delete();
    }

    public function syncPermissions($data, $id): \Spatie\Permission\Contracts\Role|Role
    {
        $role = Role::findById($id);
        return $role->syncPermissions($data);
    }
}
