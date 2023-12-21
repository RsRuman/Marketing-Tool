<?php

namespace Moveon\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $table = 'orders';

    protected $fillable = [
        'order_id',
        'name',
        'order_created_at',
        'currency_code',
        'discount_code',
        'display_fulfillment_status',
        'fully_paid',
        'original_total_additional_fees_set',
        'tags',
        'sub_total_line_items_quantity',
        'subtotal_price_set',
        'total_discounts_set',
        'total_price_set',
        'total_received_set',
        'total_shipping_price_set',
        'total_tax_set',
        'total_weight',
        'unpaid',
        'customer_id',
        'customer_locale'
    ];

    protected $casts = [
        'tags'                     => 'array',
        'subtotal_price_set'       => 'array',
        'total_discounts_set'      => 'array',
        'total_price_set'          => 'array',
        'total_received_set'       => 'array',
        'total_shipping_price_set' => 'array',
        'total_tax_set'            => 'array',
        'order_created_at'         => 'datetime',
    ];

    # Customer
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
