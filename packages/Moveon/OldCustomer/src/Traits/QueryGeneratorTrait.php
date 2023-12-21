<?php

namespace Moveon\Customer\Traits;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Moveon\Customer\Models\Customer;

trait QueryGeneratorTrait
{
    /**
     * Generate query from segment
     * And return customers
     * @throws Exception
     */
    public function query($segmentations, $perPage): LengthAwarePaginator|array
    {
        $segmentation = $segmentations[0];

        if ($segmentation && strtoupper($segmentation->name) === 'PURCHASE HISTORY') {

            # Place an order
            if (strtoupper($segmentation->segmentationCriteria->name) === 'PLACED AN ORDER') {
                if ($segmentation->segmentationCriteria->children) {
                    $children = $segmentation->segmentationCriteria->children()->first();

                    switch ($children) {
                        case strtoupper($children->name) === 'AT LEAST ONCE':
                            if ($children->children) {
                                $children = $children->children()->first();

                                switch ($children) {
                                    case strtoupper($children->name) === 'BETWEEN':
                                        list($start, $end) = explode(' to ', $children->value);
                                        return Customer::query()->whereHas('orders', function ($query) use ($start, $end) {
                                            $query->whereDate('order_created_at', '>', $start)->whereDate('order_created_at', '<', $end);
                                        })->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'BEFORE':
                                        $date = $children->value;
                                        return Customer::query()->whereHas('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '<', $date);
                                        })->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'AFTER':
                                        $date = $children->value;
                                        return Customer::query()->whereHas('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '>', $date);
                                        })->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'EXACTLY':
                                        $date = $children->value;
                                        return Customer::query()->whereHas('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '=', $date);
                                        })->paginate($perPage);
                                        break;
                                }

                            }
                            break;
                        case strtoupper($children->name) === 'AT LEAST':
                            if ($children->children) {
                                $children = $children->children()->first();

                                switch ($children) {
                                    case strtoupper($children->name) === 'BETWEEN':
                                        list($start, $end) = explode(' to ', $children->value);
                                        return Customer::query()->whereHas('orders', function ($query) use ($start, $end) {
                                            $query->whereDate('order_created_at', '>', $start)->whereDate('order_created_at', '<', $end);
                                        }, '>=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'BEFORE':
                                        $date = $children->value;
                                        return Customer::query()->whereHas('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '<', $date);
                                        }, '>=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'AFTER':
                                        $date = $children->value;
                                        return Customer::query()->whereHas('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '>', $date);
                                        }, '>=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'EXACTLY':
                                        $date = $children->value;
                                        return Customer::query()->whereHas('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '=', $date);
                                        }, '>=', (int)$children->parent->value)->paginate($perPage);
                                        break;
                                }

                            }
                            break;
                        case strtoupper($children->name) === 'AT MOST':
                            if ($children->children) {
                                $children = $children->children()->first();

                                switch ($children) {
                                    case strtoupper($children->name) === 'BETWEEN':
                                        list($start, $end) = explode(' to ', $children->value);
                                        return Customer::query()->whereHas('orders', function ($query) use ($start, $end) {
                                            $query->whereDate('order_created_at', '>', $start)->whereDate('order_created_at', '<', $end);
                                        })->has('orders', '<=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'BEFORE':
                                        $date = $children->value;
                                        return Customer::query()->whereHas('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '<', $date);
                                        })->has('orders', '<=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'AFTER':
                                        $date = $children->value;
                                        return Customer::query()->whereHas('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '>', $date);
                                        })->has('orders', '<=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'EXACTLY':
                                        $date = $children->value;
                                        return Customer::query()->whereHas('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '=', $date);
                                        })->has('orders', '<=', (int)$children->parent->value)->paginate($perPage);
                                        break;
                                }

                            }
                            break;
                        case strtoupper($children->name) === 'EXACTLY':
                            if ($children->children) {
                                $children = $children->children()->first();

                                switch ($children) {
                                    case strtoupper($children->name) === 'BETWEEN':
                                        list($start, $end) = explode(' to ', $children->value);
                                        return Customer::query()->whereHas('orders', function ($query) use ($start, $end) {
                                            $query->whereDate('order_created_at', '>', $start)->whereDate('order_created_at', '<', $end);
                                        })->has('orders', '=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'BEFORE':
                                        $date = $children->value;
                                        return Customer::query()->whereHas('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '<', $date);
                                        })->has('orders', '=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'AFTER':
                                        $date = $children->value;
                                        return Customer::query()->whereHas('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '>', $date);
                                        })->has('orders', '=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'EXACTLY':
                                        $date = $children->value;
                                        return Customer::query()->whereHas('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '=', $date);
                                        })->has('orders', '=', (int)$children->parent->value)->paginate($perPage);
                                        break;
                                }

                            }
                            break;
                    }
                }
            }

            # Not place an order
            if (strtoupper($segmentation->segmentationCriteria->name) === 'NOT PLACED AN ORDER') {
                if ($segmentation->segmentationCriteria->children) {
                    $children = $segmentation->segmentationCriteria->children()->first();

                    switch ($children) {
                        case strtoupper($children->name) === 'AT LEAST ONCE':
                            if ($children->children) {
                                $children = $children->children()->first();

                                switch ($children) {
                                    case strtoupper($children->name) === 'BETWEEN':
                                        list($start, $end) = explode(' to ', $children->value);
                                        return Customer::query()->whereDoesntHave('orders', function ($query) use ($start, $end) {
                                            $query->whereDate('order_created_at', '>', $start)->whereDate('order_created_at', '<', $end);
                                        })->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'BEFORE':
                                        $date = $children->value;
                                        return Customer::query()->whereDoesntHave('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '<', $date);
                                        })->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'AFTER':
                                        $date = $children->value;
                                        return Customer::query()->whereDoesntHave('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '>', $date);
                                        })->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'EXACTLY':
                                        $date = $children->value;
                                        return Customer::query()->whereDoesntHave('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '=', $date);
                                        })->paginate($perPage);
                                        break;
                                }

                            }
                            break;
                        case strtoupper($children->name) === 'AT LEAST':
                            if ($children->children) {
                                $children = $children->children()->first();

                                switch ($children) {
                                    case strtoupper($children->name) === 'BETWEEN':
                                        list($start, $end) = explode(' to ', $children->value);
                                        return Customer::query()->whereDoesntHave('orders', function ($query) use ($start, $end) {
                                            $query->whereDate('order_created_at', '>', $start)->whereDate('order_created_at', '<', $end);
                                        }, '>=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'BEFORE':
                                        $date = $children->value;
                                        return Customer::query()->whereDoesntHave('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '<', $date);
                                        }, '>=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'AFTER':
                                        $date = $children->value;
                                        return Customer::query()->whereDoesntHave('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '>', $date);
                                        }, '>=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'EXACTLY':
                                        $date = $children->value;
                                        return Customer::query()->whereDoesntHave('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '=', $date);
                                        }, '>=', (int)$children->parent->value)->paginate($perPage);
                                        break;
                                }

                            }
                            break;
                        case strtoupper($children->name) === 'AT MOST':
                            if ($children->children) {
                                $children = $children->children()->first();

                                switch ($children) {
                                    case strtoupper($children->name) === 'BETWEEN':
                                        list($start, $end) = explode(' to ', $children->value);
                                        return Customer::query()->whereDoesntHave('orders', function ($query) use ($start, $end) {
                                            $query->whereDate('order_created_at', '>', $start)->whereDate('order_created_at', '<', $end);
                                        })->has('orders', '<=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'BEFORE':
                                        $date = $children->value;
                                        return Customer::query()->whereDoesntHave('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '<', $date);
                                        })->has('orders', '<=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'AFTER':
                                        $date = $children->value;
                                        return Customer::query()->whereDoesntHave('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '>', $date);
                                        })->has('orders', '<=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'EXACTLY':
                                        $date = $children->value;
                                        return Customer::query()->whereDoesntHave('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '=', $date);
                                        })->has('orders', '<=', (int)$children->parent->value)->paginate($perPage);
                                        break;
                                }

                            }
                            break;
                        case strtoupper($children->name) === 'EXACTLY':
                            if ($children->children) {
                                $children = $children->children()->first();

                                switch ($children) {
                                    case strtoupper($children->name) === 'BETWEEN':
                                        list($start, $end) = explode(' to ', $children->value);
                                        return Customer::query()->whereDoesntHave('orders', function ($query) use ($start, $end) {
                                            $query->whereDate('order_created_at', '>', $start)->whereDate('order_created_at', '<', $end);
                                        })->has('orders', '=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'BEFORE':
                                        $date = $children->value;
                                        return Customer::query()->whereDoesntHave('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '<', $date);
                                        })->has('orders', '=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'AFTER':
                                        $date = $children->value;
                                        return Customer::query()->whereDoesntHave('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '>', $date);
                                        })->has('orders', '=', (int)$children->parent->value)->paginate($perPage);
                                        break;

                                    case strtoupper($children->name) === 'EXACTLY':
                                        $date = $children->value;
                                        return Customer::query()->whereDoesntHave('orders', function ($query) use ($date) {
                                            $query->whereDate('order_created_at', '=', $date);
                                        })->has('orders', '=', (int)$children->parent->value)->paginate($perPage);
                                        break;
                                }

                            }
                            break;
                    }
                }
            }
        }

        # Type
        if ($segmentation && strtoupper($segmentation->name) === 'CUSTOMER ATTRIBUTES') {
            if (strtoupper($segmentation->segmentationCriteria->name) === 'TYPE') {
                return Customer::query()->where('customer_type', $segmentation->segmentationCriteria->value)->paginate($perPage);
            }
        }

        # Tags
        if ($segmentation && strtoupper($segmentation->name) === 'TAGS') {
            if (strtoupper($segmentation->segmentationCriteria->name) === 'HAS ANY OF THESE TAGS') {
                $tags = $segmentation->segmentationCriteria->value;
                $customerIds =  DB::table('customer_tag')
                    ->orWhereIn('tag_id', function ($query) use ($tags) {
                        $query->select('id')
                            ->from('tags')
                            ->whereIn('name', $tags);
                    })
                    ->pluck('customer_id');

                return Customer::query()->whereIn('_id', $customerIds)->paginate($perPage);
            }

            if (strtoupper($segmentation->segmentationCriteria->name) === 'DOES NOT HAVE ANY OF THESE TAGS') {
                $tags = $segmentation->segmentationCriteria->value;
                $customerIds =  DB::table('customer_tag')
                    ->whereIn('tag_id', function ($query) use ($tags) {
                        $query->select('id')
                            ->from('tags')
                            ->whereIn('name', $tags);
                    })
                    ->pluck('customer_id');

                return Customer::query()->whereNotIn('_id', $customerIds)->paginate($perPage);
            }
        }

        return [];
    }
}
