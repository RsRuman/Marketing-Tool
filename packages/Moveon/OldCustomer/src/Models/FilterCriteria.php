<?php

namespace Moveon\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FilterCriteria extends Model
{
    use HasFactory;

    protected $table = 'filter_criterias';

    protected $fillable = [
        'filter_id',
        'parent_id',
        'group_id',
        'name',
        'value',
        'value_type',
        'label',
    ];

    # Filter
    public function filter(): BelongsTo
    {
        return $this->belongsTo(Filter::class, 'filter_id', 'id');
    }

    # Parent
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    # Childrens
    public function childrens(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    # Group
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
