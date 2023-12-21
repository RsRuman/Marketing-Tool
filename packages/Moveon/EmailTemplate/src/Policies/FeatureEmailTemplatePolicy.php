<?php

namespace Moveon\EmailTemplate\Policies;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
class FeatureEmailTemplatePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function addToMyTemplate(User $user): bool
    {
        return $user->hasPermissionTo('add-to-my-template');
    }
}
