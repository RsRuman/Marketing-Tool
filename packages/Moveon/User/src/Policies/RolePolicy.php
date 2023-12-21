<?php

namespace Moveon\User\Policies;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
class RolePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-role');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('admin')) {
            return $user->hasPermissionTo('create-role');
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function update(User $user): bool
    {
        if ($user->hasRole('admin')) {
            return $user->hasPermissionTo('update-role');
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function assignPermissionToRole(User $user): bool
    {
        if ($user->hasRole('admin')) {
            return $user->hasPermissionTo('assign-permission');
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        if ($user->hasRole('admin')) {
            return $user->hasPermissionTo('delete-role');
        }

        return false;
    }
}
