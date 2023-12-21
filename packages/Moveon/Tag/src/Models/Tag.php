<?php

namespace Moveon\Tag\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Moveon\Setting\Models\Lead;

class Tag extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'tags';

    protected $fillable = [
        'user_id',
        'name',
        'slug',
    ];

    public function slug(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => Str::slug($value)
        );
    }

    public function leads()
    {
        return $this->belongsToMany(Lead::class, 'lead_tag', 'tag_id', 'lead_id');
    }
}
