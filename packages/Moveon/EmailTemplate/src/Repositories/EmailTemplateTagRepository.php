<?php

namespace Moveon\EmailTemplate\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Moveon\EmailTemplate\Models\EmailTemplateTag;

class EmailTemplateTagRepository
{
    public function all(): Collection
    {
        return EmailTemplateTag::all();
    }

    public function find($id): object|null
    {
        return EmailTemplateTag::query()
            ->where('id', $id)
            ->first();
    }
}
