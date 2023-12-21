<?php

namespace Moveon\Segmentation\Database\Seeders;

use Illuminate\Database\Seeder;

class FilterCriteriaSeeder extends Seeder
{
    private $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders             = [
            [
                'is_parent'  => true,
                'key'        => 'At least',
                'label'      => 'times',
                'value'      => 1,
                'value_type' => 'integer',
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'At most',
                'label'      => 'times',
                'value'      => 1,
                'value_type' => 'integer',
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'Is greater than',
                'label'      => 'times',
                'value'      => 1,
                'value_type' => 'integer',
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'Is less than',
                'label'      => 'times',
                'value'      => 1,
                'value_type' => 'integer',
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'Is equals',
                'label'      => 'times',
                'value'      => 1,
                'value_type' => 'integer',
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'All time',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'In the last',
                'label'      => 'days',
                'value'      => 1,
                'value_type' => 'integer',
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'Last 7 days',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'Last 7 days',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'Last 30 days',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'Last 3 months',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'Last year',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'On exact date',
                'label'      => null,
                'value'      => now()->format('Y-m-d'),
                'value_type' => 'date',
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'On before',
                'label'      => null,
                'value'      => now()->format('Y-m-d'),
                'value_type' => 'date',
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'On after',
                'label'      => null,
                'value'      => now()->format('Y-m-d'),
                'value_type' => 'date',
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'Between',
                'label'      => null,
                'value'      => now()->format('Y-m-d') . ' to ' . now()->subDay()->format('Y-m-d'),
                'value_type' => 'date-between',
                'status'     => 'active',
            ],
        ];
        $wishAndCarts       = [
            [
                'is_parent'  => true,
                'key'        => 'All time',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'In the last',
                'label'      => 'days',
                'value'      => 1,
                'value_type' => 'integer',
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'Last 7 days',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'Last 30 days',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'Last 3 months',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'Last year',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'On exact date',
                'label'      => null,
                'value'      => now()->format('Y-m-d'),
                'value_type' => 'date',
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'On before',
                'label'      => null,
                'value'      => now()->format('Y-m-d'),
                'value_type' => 'date',
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'On after',
                'label'      => null,
                'value'      => now()->format('Y-m-d'),
                'value_type' => 'date',
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'Between',
                'label'      => null,
                'value'      => now()->format('Y-m-d') . ' to ' . now()->subDay()->format('Y-m-d'),
                'value_type' => 'date-between',
                'status'     => 'active',
            ],
        ];
        $customerAttributes = [
            [
                'is_parent'  => true,
                'key'        => 'Gender',
                'label'      => null,
                'value'      => 'male,female,others',
                'value_type' => 'select',
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'Post code',
                'label'      => null,
                'value'      => null,
                'value_type' => 'text',
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'Account created',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'Tag with',
                'label'      => null,
                'value'      => null,
                'value_type' => 'multi-select',
                'status'     => 'active',
            ],

            [
                'is_parent'  => true,
                'key'        => 'Not tag with',
                'label'      => null,
                'value'      => null,
                'value_type' => 'multi-select',
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'All time',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'In the last',
                'label'      => 'days',
                'value'      => 1,
                'value_type' => 'integer',
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'Last 7 days',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'Last 30 days',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'Last 3 months',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'Last year',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'On exact date',
                'label'      => null,
                'value'      => now()->format('Y-m-d'),
                'value_type' => 'date',
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'On before',
                'label'      => null,
                'value'      => now()->format('Y-m-d'),
                'value_type' => 'date',
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'On after',
                'label'      => null,
                'value'      => now()->format('Y-m-d'),
                'value_type' => 'date',
                'status'     => 'active',
            ],

            [
                'is_parent'  => false,
                'key'        => 'Between',
                'label'      => null,
                'value'      => now()->format('Y-m-d') . ' to ' . now()->subDay()->format('Y-m-d'),
                'value_type' => 'date-between',
                'status'     => 'active',
            ],
        ];
        $unknowns           = [
            [
                'filter_id'  => null,
                'is_parent'  => true,
                'key'        => 'All time',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'filter_id'  => null,
                'is_parent'  => true,
                'key'        => 'In the last',
                'label'      => 'days',
                'value'      => 1,
                'value_type' => 'integer',
                'status'     => 'active',
            ],

            [
                'filter_id'  => null,
                'is_parent'  => true,
                'key'        => 'Last 7 days',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'filter_id'  => null,
                'is_parent'  => true,
                'key'        => 'Last 30 days',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'filter_id'  => null,
                'is_parent'  => true,
                'key'        => 'Last 3 months',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'filter_id'  => null,
                'is_parent'  => true,
                'key'        => 'Last year',
                'label'      => null,
                'value'      => null,
                'value_type' => null,
                'status'     => 'active',
            ],

            [
                'filter_id'  => null,
                'is_parent'  => true,
                'key'        => 'On exact date',
                'label'      => null,
                'value'      => now()->format('Y-m-d'),
                'value_type' => 'date',
                'status'     => 'active',
            ],

            [
                'filter_id'  => null,
                'is_parent'  => true,
                'key'        => 'On before',
                'label'      => null,
                'value'      => now()->format('Y-m-d'),
                'value_type' => 'date',
                'status'     => 'active',
            ],

            [
                'filter_id'  => null,
                'is_parent'  => true,
                'key'        => 'On after',
                'label'      => null,
                'value'      => now()->format('Y-m-d'),
                'value_type' => 'date',
                'status'     => 'active',
            ],

            [
                'filter_id'  => null,
                'is_parent'  => true,
                'key'        => 'Between',
                'label'      => null,
                'value'      => now()->format('Y-m-d') . ' to ' . now()->subDay()->format('Y-m-d'),
                'value_type' => 'date-between',
                'status'     => 'active',
            ],
        ];

        # TODO: If platform type is ecommerce then we will seeds orders, wishAndCarts
        if ($this->filter->type === 'orders') {
            foreach ($orders as $order) {
                $this->filter->filterCriterias()->create($order);
            }
        }

        if ($this->filter->type === 'carts') {
            foreach ($wishAndCarts as $wishAndCart) {
                $this->filter->filterCriterias()->create($wishAndCart);
            }
        }

        if ($this->filter->type === 'wish_list') {
            foreach ($wishAndCarts as $wishAndCart) {
                $this->filter->filterCriterias()->create($wishAndCart);
            }
        }

        if ($this->filter->type === 'customers') {
            foreach ($customerAttributes as $customerAttribute) {
                $this->filter->filterCriterias()->create($customerAttribute);
            }
        }
    }
}
