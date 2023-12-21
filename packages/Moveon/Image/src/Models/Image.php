<?php

namespace Moveon\Image\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Image extends Model
{
    protected $table = 'images';
    protected $fillable = [
        'user_id',
        'created_by_id',
        'updated_by_id',
        'name',
        'type',
        'link'
    ];

    const DEFAULT_CATEGORY = 10;

    # Categories
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_image');
    }

    # Created By
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    # Update By
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id', 'id');
    }
}
