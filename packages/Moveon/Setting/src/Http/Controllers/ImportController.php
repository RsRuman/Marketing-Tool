<?php

namespace Moveon\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Moveon\Setting\Http\Requests\ImportRequest;
use Moveon\Setting\Models\Lead;
use Moveon\Setting\Services\ImportService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ImportController extends Controller
{
    private ImportService $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * Import data
     * @param ImportRequest $request
     * @param $slug
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function import(ImportRequest $request, $slug): JsonResponse
    {
        # Check authorization
        $this->authorize('import', Lead::class);

        try {
            # TODO: Get platform type from Platform model. Do not need to compare with slug
            if ($slug !== 'private-platform') {
                return Response::json([
                    'error' => 'Platform do not support custom import.'
                ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
            }

            $this->importService->importPrivatePlatformData($request);

            return Response::json([
                'message' => 'Data is being imported. Please be patient; we will notify you after it is complete.'
            ], ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return Response::json([
                'error' => $ex->getMessage()
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
