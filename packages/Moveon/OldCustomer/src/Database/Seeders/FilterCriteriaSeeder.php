<?php

namespace Moveon\Customer\Database\Seeders;

use Illuminate\Database\Seeder;
use Moveon\Customer\Models\Filter;
use Moveon\Customer\Models\FilterCriteria;
use Moveon\Customer\Models\Group;

class FilterCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purchaseHistoryFilter   = Filter::query()->where('name', 'Purchase history')->first();
        $customerAttributeFilter = Filter::query()->where('name', 'Customer attributes')->first();
        $fixedDateGroup          = Group::query()->where('name', 'Fixed Dates')->first();
        $customerTagFilter       = Filter::query()->where('name', 'Tags')->first();

        $parentCriterias = [
            # For filter
            # Placed an Order
            "place_an_order"     => [
                'filter_id'  => $purchaseHistoryFilter->id,
                'parent_id'  => null,
                'group_id'   => null,
                'name'       => 'placed an order',
                'value'      => null,
                'value_type' => null,
                'label'      => null,
            ],

            # Not placed aa order
            "not_place_an_order" => [
                'filter_id'  => $purchaseHistoryFilter->id,
                'parent_id'  => null,
                'group_id'   => null,
                'name'       => 'not placed an order',
                'value'      => null,
                'value_type' => null,
                'label'      => null,
            ],

            "type" => [
                'filter_id'  => $customerAttributeFilter->id,
                'parent_id'  => null,
                'group_id'   => null,
                'name'       => 'type',
                'value'      => 'shopify',
                'value_type' => 'string',
                'label'      => null,
            ],

            "has_any_of_these_tags" => [
                'filter_id'  => $customerTagFilter->id,
                'parent_id'  => null,
                'group_id'   => null,
                'name'       => 'has any of these tags',
                'value'      => null,
                'value_type' => 'array',
                'label'      => null,
            ],

            "does_not_have_any_of_these_tags" => [
                'filter_id'  => $customerTagFilter->id,
                'parent_id'  => null,
                'group_id'   => null,
                'name'       => 'does not have any of these tags',
                'value'      => null,
                'value_type' => 'array',
                'label'      => null,
            ],
        ];

        $childCriterias = [

            # For placed an order
            # At least once
            [
                'filter_id'  => null,
                'parent_id'  => null,
                'group_id'   => null,
                'name'       => 'at least once',
                'value'      => null,
                'value_type' => null,
                'label'      => null,
            ],

            # At least
            [
                'filter_id'  => null,
                'parent_id'  => null,
                'group_id'   => null,
                'name'       => 'at least',
                'value'      => '1',
                'value_type' => 'integer',
                'label'      => 'times',
            ],

            # At most
            [
                'filter_id'  => null,
                'parent_id'  => null,
                'group_id'   => null,
                'name'       => 'at most',
                'value'      => '1',
                'value_type' => 'integer',
                'label'      => 'times',
            ],

            # Exactly
            [
                'filter_id'  => null,
                'parent_id'  => null,
                'group_id'   => null,
                'name'       => 'exactly',
                'value'      => '1',
                'value_type' => 'integer',
                'label'      => 'times',
            ],
        ];

        $childTwoCriterias = [
            [
                'filter_id'  => null,
                'parent_id'  => null,
                'group_id'   => $fixedDateGroup->id,
                'name'       => 'between',
                'value'      => '2023-09-03 to 2023-09-02',
                'value_type' => 'date_between',
                'label'      => null,
            ],

            [
                'filter_id'  => null,
                'parent_id'  => null,
                'group_id'   => $fixedDateGroup->id,
                'name'       => 'before',
                'value'      => '2023-09-03',
                'value_type' => 'date',
                'label'      => null,
            ],

            [
                'filter_id'  => null,
                'parent_id'  => null,
                'group_id'   => $fixedDateGroup->id,
                'name'       => 'after',
                'value'      => '2023-09-03',
                'value_type' => 'date',
                'label'      => null,
            ],

            [
                'filter_id'  => null,
                'parent_id'  => null,
                'group_id'   => $fixedDateGroup->id,
                'name'       => 'on',
                'value'      => '2023-09-03',
                'value_type' => 'date',
                'label'      => null,
            ],

        ];

        $notPlaceAnOrderFilterCriteria = FilterCriteria::query()->whereNotNull('filter_id')->where('name', 'not placed an order')->first();
        $placeAnOrder                  = FilterCriteria::query()->whereNotNull('filter_id')->where('name', 'placed an order')->first();
        $typeFilterCriteria            = FilterCriteria::query()->whereNotNull('filter_id')->where('name', 'type')->first();
        $hasAnyTagFilterCriteria       = FilterCriteria::query()->whereNotNull('filter_id')->where('name', 'has any of these tags')->first();
        $notHasAnyTagFilterCriteria    = FilterCriteria::query()->whereNotNull('filter_id')->where('name', 'does not have any of these tags')->first();

        if (!$placeAnOrder) {
            $placeAnOrder = FilterCriteria::create($parentCriterias['place_an_order']);
        }

        if (!$notPlaceAnOrderFilterCriteria) {
            $notPlaceAnOrderFilterCriteria = FilterCriteria::create($parentCriterias['not_place_an_order']);
        }

        if (!$typeFilterCriteria) {
            FilterCriteria::create($parentCriterias['type']);
        }

        if ($placeAnOrder) {
            $childCriterias = array_map(function ($criteria) use ($placeAnOrder) {
                $criteria['parent_id'] = $placeAnOrder->id;
                return $criteria;
            }, $childCriterias);

            foreach ($childCriterias as $criteria) {
                $exist = FilterCriteria::query()->where('name', $criteria['name'])->where('parent_id', $criteria['parent_id'])->first();
                if (!$exist) {
                    $fc = FilterCriteria::create($criteria);

                    foreach ($childTwoCriterias as $childTwoCriteria) {
                        $childTwoCriteria['parent_id'] = $fc->id;
                        FilterCriteria::create($childTwoCriteria);
                    }
                }
            }
        }

        if ($notPlaceAnOrderFilterCriteria) {
            $childCriterias = array_map(function ($criteria) use ($notPlaceAnOrderFilterCriteria) {
                $criteria['parent_id'] = $notPlaceAnOrderFilterCriteria->id;
                return $criteria;
            }, $childCriterias);

            foreach ($childCriterias as $criteria) {
                $exist = FilterCriteria::query()->where('name', $criteria['name'])->where('parent_id', $criteria['parent_id'])->first();
                if (!$exist) {
                    $fc = FilterCriteria::create($criteria);

                    foreach ($childTwoCriterias as $childTwoCriteria) {
                        $childTwoCriteria['parent_id'] = $fc->id;
                        FilterCriteria::create($childTwoCriteria);
                    }
                }
            }
        }

        if (!$hasAnyTagFilterCriteria) {
            FilterCriteria::create($parentCriterias['has_any_of_these_tags']);
        }

        if (!$notHasAnyTagFilterCriteria) {
            FilterCriteria::create($parentCriterias['does_not_have_any_of_these_tags']);
        }
    }
}
