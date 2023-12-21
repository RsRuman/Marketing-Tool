<?php

namespace Moveon\Setting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $table = 'events';

    protected $fillable = [
        'user_id',
        'type',
        'customer_id',
        'event_created_at',
        'sub_total_price',
        'total_tax',
        'total_discount',
        'total_price',
        'fully_paid',
        'item_ids',
        'product_id',
        'price_range',
        'image_url'
    ];

    protected $casts = [
        'event_created_at' => 'datetime',
        'item_ids'         => 'array',
    ];

    const TYPE_ORDERS = 'orders';
    const TYPE_CARTS = 'carts';
    const TYPE_WISH_LIST = 'wish_list';
    const TYPE_PRODUCTS = 'products';
    const TYPE_CUSTOMERS = 'customers';
    const TYPE = [
        self::TYPE_ORDERS,
        self::TYPE_CARTS,
        self::TYPE_WISH_LIST,
        self::TYPE_PRODUCTS,
        self::TYPE_CUSTOMERS,
    ];
}
