<?php

namespace Moveon\User\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Moveon\User\Events\CreateAdminRoleEvent;
use Spatie\Permission\Models\Permission;

class CreateAdminRoleListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(CreateAdminRoleEvent $event): void
    {
        $role = $event->user->roles()->first();
        $permissions = Permission::all()->pluck('id');
        $role->syncPermissions($permissions);
    }
}
