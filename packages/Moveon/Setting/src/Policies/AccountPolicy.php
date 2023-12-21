<?php

namespace Moveon\Setting\Policies;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
class AccountPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-account');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-account');
    }
}
