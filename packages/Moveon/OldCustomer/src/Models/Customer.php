<?php

namespace Moveon\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\HasMany;
use Moveon\Tag\Models\Tag;

class Customer extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $table      = 'customers';

    protected $fillable = [
        'customer_id',
        'email',
        'first_name',
        'last_name',
        'tags',
        'phone',
        'last_order',
        'amount_spent',
        'number_of_orders',
        'average_order_amount',
        'customer_type',
    ];

    protected $casts = [
        'amount_spent' => 'array',
        'tags'         => 'array'
    ];

    # Orders
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id', 'customer_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'customer_tag', 'customer_id', 'tag_id');
    }
}
