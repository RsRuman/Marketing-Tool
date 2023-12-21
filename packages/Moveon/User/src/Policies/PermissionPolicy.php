<?php

namespace Moveon\User\Policies;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-permission');
    }
}
