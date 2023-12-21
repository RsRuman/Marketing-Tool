<?php

namespace Moveon\User\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('admin')) {
            return $user->hasPermissionTo('create-user');
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
            return $user->hasPermissionTo('update-user');
        }
    }

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-user');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function inactiveUser(User $user): bool
    {
        if ($user->hasRole('admin')) {
            return $user->hasPermissionTo('inactive-user');
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function assignRole(User $user): bool
    {
        if ($user->hasRole('admin')) {
            return $user->hasPermissionTo('assign-role');;
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewUserSetting(User $user): bool
    {
        return $user->hasPermissionTo('view-user-setting');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function editUserSetting(User $user): bool
    {
        return $user->hasPermissionTo('edit-user-setting');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function resetPassword(User $user): bool
    {
        return $user->hasPermissionTo('reset-password');
    }
}
