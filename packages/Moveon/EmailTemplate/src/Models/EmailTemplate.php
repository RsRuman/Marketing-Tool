<?php

namespace Moveon\EmailTemplate\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'email_templates';
    protected $fillable = [
        'user_id',
        'created_by_id',
        'updated_by_id',
        'name',
        'design',
        'html',
        'status'
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVATE = 'activate';

    const STATUS = [
        self::STATUS_DRAFT,
        self::STATUS_ACTIVATE,
    ];

    const SORT_BY_NAME = 'name';
    const SORT_BY_DATE = 'date';

    const SORT_BY = [
        self::SORT_BY_NAME,
        self::SORT_BY_DATE,
    ];

    protected $casts = [
        'design' => 'array'
    ];

    # Created BY
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    # Updated By
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id', 'id');
    }

}
