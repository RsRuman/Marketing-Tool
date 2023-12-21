<?php

namespace Moveon\Segmentation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Segmentation extends Model
{
    use HasFactory;

    protected $table = 'segmentations';
    protected $fillable = [
        'user_segmentation_id',
        'key',
        'label',
        'type',
        'status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE
    ];

    # User segmentation
    public function userSegmentation(): BelongsTo
    {
        return $this->belongsTo(UserSegmentation::class, 'user_segmentation_id', 'id');
    }

    # SegmentationCriteria
    public function segmentationCriteria(): HasOne
    {
        return $this->hasOne(SegmentationCriteria::class, 'segmentation_id', 'id');
    }

    # SegmentationCriterias
    public function segmentationCriterias(): HasMany
    {
        return $this->hasMany(SegmentationCriteria::class, 'segmentation_id', 'id');
    }
}
