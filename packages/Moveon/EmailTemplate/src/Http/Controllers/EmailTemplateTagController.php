<?php

namespace Moveon\EmailTemplate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Moveon\EmailTemplate\Services\EmailTemplateTagService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EmailTemplateTagController extends Controller
{
    private EmailTemplateTagService $emailTemplateTagService;

    public function __construct(EmailTemplateTagService $emailTemplateTagService) {
        $this->emailTemplateTagService = $emailTemplateTagService;
    }

    /**
     * List of email template tags
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        # Get data
        $tags = $this->emailTemplateTagService->getTemplateTags();

        # Transforming data
        $updatedTags = [];
        foreach ($tags as $item) {;
            $updatedTags[$item->title] = $item->value;
        }

        # Return response
        return Response::json([
            "data" => $updatedTags,
        ], ResponseAlias::HTTP_OK);
    }
}
