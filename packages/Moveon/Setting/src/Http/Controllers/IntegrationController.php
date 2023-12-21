<?php

namespace Moveon\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Moveon\Setting\Http\Resources\IntegrationResource;
use Moveon\Setting\Http\Resources\IntegrationShortResource;
use Moveon\Setting\Models\Integration;
use Moveon\Setting\Services\IntegrationService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class IntegrationController extends Controller
{
    private IntegrationService $integrationService;

    public function __construct(IntegrationService $integrationService)
    {
        $this->integrationService = $integrationService;
    }

    /**
     * List of integrations
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Integration::class);

        # Get data
        $integrations = $this->integrationService->getIntegrations();

        # Transform data
        $integrations = IntegrationShortResource::collection($integrations);

        # Return data
        return Response::json([
            'data' => $integrations
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Get an integration
     * @param $slug
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show($slug): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Integration::class);

        $integration = $this->integrationService->getIntegration($slug);

        if (!$integration) {
            return Response::json([
                'error' => 'Not found.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        # Transform data
        $integration = new IntegrationResource($integration);

        # Return response
        return Response::json([
            'data' => $integration
        ], ResponseAlias::HTTP_OK);
    }
}
