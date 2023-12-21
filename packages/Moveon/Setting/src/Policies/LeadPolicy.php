<?php

namespace Moveon\Setting\Policies;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
class LeadPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function import(User $user): bool
    {
        return $user->hasPermissionTo('import-data');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-customer');
    }
}
