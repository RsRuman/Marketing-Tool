<?php

namespace Moveon\Platform\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Platform extends Model
{
    use HasFactory;

    protected $table = 'platforms';
    protected $fillable = [
        'name',
        'user_id',
        'type',
        'email',
        'domain',
        'website',
        'shop_id',
        'access_token',
        'scope'
    ];

    const SHOPIFY = 'shopify';
    const WOOCOMMERCE = 'woocommerce';

    const PLATFORM_TYPE = [
        self::SHOPIFY,
        self::WOOCOMMERCE,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
