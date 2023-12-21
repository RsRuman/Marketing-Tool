<?php

namespace Moveon\Setting\Database\Seeders;

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
            'view-account',
            'edit-account',
            'view-user-setting',
            'edit-user-setting',
            'reset-password',
            'view-integration',
            'import-data',
            'view-customer',
        ];

        foreach ($permissions as $permission) {
            $existPermission = Permission::query()->where('name', $permission)->first();
            if (!$existPermission) {
                Permission::create(['name' => $permission, 'guard_name' => 'api']);
            }
        }
    }
}
