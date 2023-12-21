<?php

namespace Moveon\Customer\Database\Seeders;

use Illuminate\Database\Seeder;
use Moveon\Customer\Models\Filter;
use Moveon\Customer\Models\Group;

class FilterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orderAndProductGroup = Group::query()->where('name', 'Orders & Products')->first();
        $customerGroup        = Group::query()->where('name', 'Customers')->first();

        $filters = [
            [
                'group_id' => $orderAndProductGroup->id,
                'name'     => 'Purchase history',
                'label'    => 'Person has',
                'status'   => 'active',
            ],

            [
                'group_id' => $orderAndProductGroup->id,
                'name'     => 'Product view',
                'label'    => 'Person has viewed',
                'status'   => 'active',
            ],

            [
                'group_id' => $customerGroup->id,
                'name'     => 'Customer attributes',
                'label'    => 'Person',
                'status'   => 'active',
            ],

            [
                'group_id' => $customerGroup->id,
                'name'     => 'Tags',
                'label'    => 'Person',
                'status'   => 'active',
            ],
        ];


        foreach ($filters as $filter) {
            $existFilter = Filter::query()->where('name', $filter['name'])->first();
            if (!$existFilter) {
                Filter::create($filter);
            }
        }
    }
}
