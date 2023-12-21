<?php

namespace Moveon\Image\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'slug',
    ];

    # Images
    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'category_image');
    }
}
