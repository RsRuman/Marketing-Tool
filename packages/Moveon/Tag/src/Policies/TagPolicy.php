<?php

namespace Moveon\Tag\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-tag');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-tag');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('delete-tag');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-tag');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function attachTag(User $user): bool
    {
        return $user->hasPermissionTo('attach-tag');
    }
}
