<?php

namespace Moveon\Customer\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Segmentation extends Model
{
    use HasFactory;

    protected $table = 'segmentations';
    protected $fillable = [
        'user_segmentation_id',
        'group_id',
        'name',
        'label',
        'status'
    ];

    # UserSegmentation
    public function userSegmentation(): BelongsTo
    {
        return $this->belongsTo(UserSegmentation::class, 'user_segmentation_id', 'id');
    }

    # Group
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    # SegmentationCriteria
    public function segmentationCriteria(): HasOne
    {
        return $this->hasOne(SegmentationCriteria::class, 'segmentation_id', 'id');
    }

    # Users
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'segmentation_user');
    }
}
