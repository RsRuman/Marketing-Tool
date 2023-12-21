<?php

namespace Moveon\EmailTemplate\Database\Seeders;

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
            'create-email-template',
            'view-email-template',
            'edit-email-template',
            'delete-email-template',
            'add-to-my-template'
        ];

        foreach ($permissions as $permission) {
            $existPermission = Permission::query()->where('name', $permission)->first();
            if (!$existPermission) {
                Permission::create(['name' => $permission, 'guard_name' => 'api']);
            }
        }
    }
}
