<?php

namespace Moveon\Segmentation\Database\Seeders;

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
            'view-segmentation',
            'create-segmentation',
            'edit-segmentation',
            'delete-segmentation',
        ];

        foreach ($permissions as $permission) {
            $existPermission = Permission::query()->where('name', $permission)->first();
            if (!$existPermission) {
                Permission::create(['name' => $permission, 'guard_name' => 'api']);
            }
        }
    }
}
