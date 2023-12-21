<?php

namespace Moveon\EmailTemplate\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Moveon\EmailTemplate\Repositories\EmailTemplateRepository;

class EmailTemplateService
{
    private EmailTemplateRepository $emailTemplateRepository;

    public function __construct(EmailTemplateRepository $emailTemplateRepository) {
        $this->emailTemplateRepository = $emailTemplateRepository;
    }

    /**
     * Get all email template
     * @param $request
     * @return LengthAwarePaginator
     */
    public function getTemplates($request): LengthAwarePaginator
    {
        return $this->emailTemplateRepository->all($request);
    }

    /**
     * Get a email template
     * @param $id
     * @return object|null
     */
    public function getTemplate($id): object|null
    {
        return $this->emailTemplateRepository->find($id);
    }

    /**
     * Create email template
     * @param $request
     * @return mixed
     */
    public function createTemplate($request): mixed
    {
        # Sanitize data
        $data = $request->only('name', 'design', 'html', 'status');
        $data['user_id']       = Auth::user()->origin->id;
        $data['created_by_id'] = Auth::user()->id;

        return $this->emailTemplateRepository->create($data);
    }

    /**
     * update email template
     * @param $request
     * @param $id
     * @return object|bool|null
     */
    public function updateTemplate($request, $id): object|null|bool
    {
       $template = $this->emailTemplateRepository->find($id);

       if (!$template) {
           return null;
       }

       # Sanitize data
       $data = $request->only('name', 'design', 'html', 'status');
       $data['updated_by_id'] = Auth::user()->id;

       if (!$this->emailTemplateRepository->update($data, $template)) {
           return false;
       }

       return $template->fresh();
    }

    /**
     * Delete email template
     * @param $id
     * @return bool|null
     */
    public function deleteTemplate($id): ?bool
    {
        $template = $this->emailTemplateRepository->find($id);

        if (!$template) {
            return null;
        }

        return $this->emailTemplateRepository->delete($id);
    }
}
