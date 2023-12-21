<?php

namespace Moveon\Segmentation\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SegmentationPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-segmentation');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-segmentation');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-segmentation');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('delete-segmentation');
    }
}
