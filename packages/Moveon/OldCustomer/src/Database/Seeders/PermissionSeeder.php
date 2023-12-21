<?php

namespace Moveon\Customer\Database\Seeders;

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
            'view-customer',
            'view-segmentation',
            'create-segmentation',
            'edit-segmentation',
            'delete-segmentation',
            'export-customer',
            'import-customer',
        ];

        foreach ($permissions as $permission) {
            $existPermission = Permission::query()->where('name', $permission)->first();
            if (!$existPermission) {
                Permission::create(['name' => $permission, 'guard_name' => 'api']);
            }
        }
    }
}
