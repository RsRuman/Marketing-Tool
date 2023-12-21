<?php

namespace Moveon\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempData extends Model
{
    use HasFactory;

    protected $table = 'temp_data';
    protected $fillable = [
        'user_id',
        'token'
    ];
}
