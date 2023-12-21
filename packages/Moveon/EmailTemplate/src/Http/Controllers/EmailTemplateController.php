<?php

namespace Moveon\EmailTemplate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Moveon\EmailTemplate\Http\Requests\EmailTemplateListRequest;
use Moveon\EmailTemplate\Http\Requests\EmailTemplateStoreRequest;
use Moveon\EmailTemplate\Http\Requests\EmailTemplateUpdateRequest;
use Moveon\EmailTemplate\Http\Resources\EmailTemplateResource;
use Moveon\EmailTemplate\Mail\CampaignMail;
use Moveon\EmailTemplate\Models\EmailTemplate;
use Moveon\EmailTemplate\Models\EmailTemplateTag;
use Moveon\EmailTemplate\Services\EmailTemplateService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EmailTemplateController extends Controller
{
    private EmailTemplateService $emailTemplateService;

    public function __construct(EmailTemplateService $emailTemplateService) {
        $this->emailTemplateService = $emailTemplateService;
    }

    /**
     * List of email templates
     * @param EmailTemplateListRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(EmailTemplateListRequest $request): JsonResponse
    {
        # Validate Request
        $this->authorize('view', EmailTemplate::class);

        # Get data
        $templates = $this->emailTemplateService->getTemplates($request);

        # Transform data
        $templates = EmailTemplateResource::collection($templates);

        # Build collection response for pagination
        $response = $this->collectionResponse($templates);

        # Return response
        return Response::json($response, ResponseAlias::HTTP_OK);
    }

    /**
     * Single email template
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        # Get data
        $template = $this->emailTemplateService->getTemplate($id);

        # Check data
        if (!$template) {
            # Return response
            return Response::json([
                'error' => 'Not found!'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        # Transform data
        $template = new EmailTemplateResource($template);

        # Return response
        return Response::json([
            'data' => $template
        ], ResponseAlias::HTTP_OK);
    }

    /** Storing email template
     * @param EmailTemplateStoreRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(EmailTemplateStoreRequest $request): JsonResponse
    {
        # Check authorization
        $this->authorize('create', EmailTemplate::class);

        # Create data
        $templates = $this->emailTemplateService->createTemplate($request);

        # Transform data
        $templates = new EmailTemplateResource($templates);

        # Return response
        return Response::json([
            'data' => $templates
        ], ResponseAlias::HTTP_CREATED);
    }

    /**
     * Updating email template
     * @param EmailTemplateUpdateRequest $request
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(EmailTemplateUpdateRequest $request, $id): JsonResponse
    {
        # Check authorization
        $this->authorize('edit', EmailTemplate::class);

        # Update data
        $templateU = $this->emailTemplateService->updateTemplate($request, $id);

        # Check data
        if (is_null($templateU)) {
            # Return response
            return Response::json([
                'error' => 'Not found'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if (!$templateU) {
            # Return response
            return Response::json([
                'error' => 'Not found'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        # Transform data
        $templateU = new EmailTemplateResource($templateU);

        # Return response
        return Response::json([
            'data' => $templateU
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Deleting email template
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy($id): JsonResponse
    {
        # Check authorization
        $this->authorize('edit', EmailTemplate::class);

        # Delete data
        $template = $this->emailTemplateService->deleteTemplate($id);

        # Check data
        if (!$template) {
            # Return response
            return Response::json([
                'error' => 'Not found'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        # Return response
        return Response::json([
            'message' => 'Template deleted successfully!'
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * @return void
     */
    public function sendMail() {
        # Get data
        $template = $this->emailTemplateService->getTemplate(7);
        $tags = EmailTemplateTag::select('value')->get();
        $placeholders = [];
        foreach ($tags as $tag) {
            switch ($tag) {
                case $tag->value['value'] === '{{first_name}}':
                    $placeholders[] = [$tag->value['value'] => 'John'];
                    break;
                case  $tag->value['value'] === '{{last_name}}':
                    $placeholders[] = [$tag->value['value'] => 'Doe'];
                    break;
                case $tag->value['value'] === '{{company_name}}':
                    $placeholders[] = [$tag->value['value'] => 'SpaceX'];
                    break;
            }
        }
        $emailTemplate = Str::replacePlaceholder($template->html, $placeholders);
        Mail::to('user@example.com')->send(new CampaignMail($emailTemplate));
    }
}
