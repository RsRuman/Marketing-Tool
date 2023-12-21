<?php

namespace Moveon\EmailTemplate\Policies;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
class EmailTemplatePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-email-template');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-email-template');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-email-template');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('delete-email-template');
    }
}
