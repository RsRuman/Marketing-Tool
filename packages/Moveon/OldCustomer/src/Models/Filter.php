<?php

namespace Moveon\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filter extends Model
{
    use HasFactory;

    protected $table = 'filters';

    protected $fillable = [
        'group_id',
        'name',
        'label',
        'status'
    ];

    # Group
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    # Filter Criterias
    public function filterCriterias(): HasMany
    {
        return $this->hasMany(FilterCriteria::class, 'filter_id', 'id');
    }
}
