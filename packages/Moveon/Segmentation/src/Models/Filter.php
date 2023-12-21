<?php

namespace Moveon\Segmentation\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filter extends Model
{
    use HasFactory;

    protected $table = 'filters';
    protected $fillable = [
        'user_id',
        'key',
        'label',
        'type',
        'status',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    const STATUS = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE
    ];

    protected function label(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtolower($value),
        );
    }

    # User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    # Filter criterias
    public function filterCriterias(): HasMany
    {
        return $this->hasMany(FilterCriteria::class, 'filter_id', 'id');
    }
}
