<?php

namespace Moveon\Customer\OldPolicies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-customer');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function exportCustomer(User $user): bool
    {
        return $user->hasPermissionTo('export-customer');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function importCustomer(User $user): bool
    {
        return $user->hasPermissionTo('import-customer');
    }
}
