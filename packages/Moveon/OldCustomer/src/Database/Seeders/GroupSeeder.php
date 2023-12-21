<?php

namespace Moveon\Customer\Database\Seeders;

use Illuminate\Database\Seeder;
use Moveon\Customer\Models\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            'Orders & Products',
            'Fixed Dates',
            'Customers'
        ];

        foreach ($groups as $group) {
            $exist = Group::query()->where('name', $group)->first();

            if (!$exist) {
                Group::create([
                    'name'   => $group,
                    'status' => 'active'
                ]);
            }
        }
    }
}
