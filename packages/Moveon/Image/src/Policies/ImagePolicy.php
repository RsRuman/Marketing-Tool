<?php

namespace Moveon\Image\Policies;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
class ImagePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-image');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-image');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-image');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('delete-image');
    }
}
