<?php

namespace Moveon\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $table = 'products';

    protected $fillable = [
        'user_id',
        'type',
        'product_id',
        'title',
        'price_range',
        'image_url',
        'event_created_at',
    ];

    protected $casts = [
        'price_range' => 'array',
    ];

    # Customer
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
