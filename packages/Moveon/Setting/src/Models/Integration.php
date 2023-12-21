<?php

namespace Moveon\Setting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    use HasFactory;

    protected $table = 'integrations';
    protected $fillable = [
        'name',
        'slug',
        'type',
        'logo',
        'details',
        'instructions',
        'status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    const STATUS = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
    ];
}
