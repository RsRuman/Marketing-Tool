<?php

namespace Moveon\Segmentation\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Moveon\Segmentation\Models\Filter;

class FilterSeeder extends Seeder
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filters = [
            [
                'key'    => 'orders',
                'label'  => 'orders',
                'type'   => 'orders',
                'status' => 'active',
            ],

            [
                'key'    => 'abandon_cart',
                'label'  => 'Abandon cart',
                'type'   => 'carts',
                'status' => 'active',
            ],

            [
                'key'    => 'customer_attributes',
                'label'  => 'Customer attributes',
                'type'   => 'customers',
                'status' => 'active',
            ],

            [
                'key'    => 'wish_list',
                'label'  => 'Wish list',
                'type'   => 'wish_list',
                'status' => 'active',
            ]
        ];

        foreach ($filters as $filter) {
            $existFilter = Filter::query()->where('user_id', $this->user->id)->where('key', $filter['key'])->first();
            if (!$existFilter) {
                $filter = $this->user->filters()->create($filter);
                $filterCriteria = new FilterCriteriaSeeder($filter);
                $filterCriteria->run();
            }
        }
    }
}
