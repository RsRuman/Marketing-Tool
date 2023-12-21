<?php

namespace Moveon\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserSegmentation extends Model
{
    use HasFactory;

    protected $table = 'user_segmentations';
    protected $fillable = [
        'user_id',
        'created_by_id',
        'updated_by_id',
        'name',
        'slug',
    ];

    # Segmentations
    public function segmentations(): HasMany
    {
        return $this->hasMany(Segmentation::class, 'user_segmentation_id', 'id');
    }

}
