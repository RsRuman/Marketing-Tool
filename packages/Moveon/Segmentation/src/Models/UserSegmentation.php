<?php

namespace Moveon\Segmentation\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserSegmentation extends Model
{
    use HasFactory;

    protected $table = 'user_segmentations';
    protected $fillable = [
        'user_id',
        'created_by',
        'updated_by',
        'name',
        'slug',
        'status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE
    ];

    # Segmentations
    public function segmentations(): HasMany
    {
        return $this->hasMany(Segmentation::class, 'user_segmentation_id', 'id');
    }

    public function segmentation(): HasOne
    {
        return $this->hasOne(Segmentation::class, 'user_segmentation_id', 'id');
    }

    # User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
