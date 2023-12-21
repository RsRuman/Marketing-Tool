<?php

namespace Moveon\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create-user',
            'update-user',
            'view-user',
            'inactive-user',
            'assign-role',
            'create-role',
            'view-role',
            'update-role',
            'delete-role',
            'assign-permission',
            'view-permission',
        ];

        foreach ($permissions as $permission) {
            $existPermission = Permission::query()->where('name', $permission)->first();
            if (!$existPermission) {
                Permission::create(['name' => $permission, 'guard_name' => 'api']);
            }
        }
    }
}
