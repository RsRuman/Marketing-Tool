<?php

namespace Moveon\Segmentation\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FilterCriteria extends Model
{
    use HasFactory;

    protected $table = 'filter_criterias';
    protected $fillable = [
        'filter_id',
        'is_parent',
        'key',
        'label',
        'value',
        'value_type',
        'status',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    const STATUS = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE
    ];

    protected function key(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtolower($value),
        );
    }


    # Filter
    public function filter(): BelongsTo
    {
        return $this->belongsTo(Filter::class, 'filter_id', 'id');
    }
}
