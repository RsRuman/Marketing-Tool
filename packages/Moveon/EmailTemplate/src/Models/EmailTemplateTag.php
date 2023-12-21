<?php


namespace Moveon\EmailTemplate\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplateTag extends Model
{

    protected $table = 'email_template_tags';
    protected $fillable = [
        'title',
        'value',
        'status'
    ];

    protected $casts = [
        'value' => 'array'
    ];

}
