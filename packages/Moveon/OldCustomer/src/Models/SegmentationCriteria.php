<?php

namespace Moveon\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SegmentationCriteria extends Model
{
    use HasFactory;

    protected $table = 'segmentation_criterias';
    protected $fillable = [
        'segmentation_id',
        'parent_id',
        'group_id',
        'name',
        'value',
        'value_type',
        'label',
    ];

    protected $casts = [
        'value' => 'array'
    ];

    # Segmentation
    public function segmentation(): BelongsTo
    {
        return $this->belongsTo(Segmentation::class, 'segmentation_id', 'id');
    }

    # Parent
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    # Children
    public function children(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(self::class, 'parent_id');
    }

    # Group
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
