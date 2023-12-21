<?php

namespace Moveon\EmailTemplate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeatureEmailTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'feature_email_templates';
    protected $fillable = [
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

}
