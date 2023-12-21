<?php

namespace Moveon\Setting\Policies;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
class IntegrationPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-integration');
    }
}
