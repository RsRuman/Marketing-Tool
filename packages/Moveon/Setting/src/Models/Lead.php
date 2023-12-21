<?php

namespace Moveon\Setting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Moveon\Tag\Models\Tag;

class Lead extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $table = 'leads';

    protected $fillable = [
        'user_id',
        'customer_id',
        'first_name',
        'last_name',
        'name',
        'gender',
        'email',
        'phone',
        'post_code',
        'event_created_at'
    ];

    protected $casts = [
        'event_created_at' => 'datetime',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'lead_tag', 'lead_id', 'tag_id');
    }
}
