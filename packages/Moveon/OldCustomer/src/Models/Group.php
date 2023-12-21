<?php

namespace Moveon\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';

    protected $fillable = [
        'name',
        'status',
    ];

    # Filters
    public function filters(): HasMany
    {
        return $this->hasMany(Filter::class, 'group_id', 'id');
    }

    # Filter Criterias
    public function filterCriterias(): HasMany
    {
        return $this->hasMany(FilterCriteria::class, 'group_id', 'id');
    }

    # Segmentations
    public function segmentations(): HasMany
    {
        return $this->hasMany(Segmentation::class, 'group_id', 'id');
    }
}
