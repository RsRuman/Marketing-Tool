<?php

namespace Moveon\EmailTemplate\Services;

use Illuminate\Database\Eloquent\Collection;
use Moveon\EmailTemplate\Repositories\EmailTemplateTagRepository;

class EmailTemplateTagService
{
    private EmailTemplateTagRepository $emailTemplateTagRepository;

    public function __construct(EmailTemplateTagRepository $emailTemplateTagRepository)
    {
        $this->emailTemplateTagRepository = $emailTemplateTagRepository;
    }


    public function getTemplateTags(): Collection
    {
        return $this->emailTemplateTagRepository->all();
    }

}
