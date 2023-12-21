<?php

namespace Moveon\Segmentation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SegmentationCriteria extends Model
{
    use HasFactory;

    protected $table = 'segmentation_criterias';
    protected $fillable = [
        'segmentation_id',
        'is_parent',
        'key',
        'label',
        'value',
        'value_type',
        'status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE
    ];

    # Segmentation
    public function segmentation(): BelongsTo
    {
        return $this->belongsTo(Segmentation::class, 'segmentation_id', 'id');
    }
}
