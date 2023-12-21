<?php

namespace Moveon\Lead\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Moveon\Lead\Http\Requests\LeadListRequest;
use Moveon\Lead\Http\Resources\LeadResource;
use Moveon\Lead\Services\LeadService;
use Moveon\Setting\Models\Lead;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class LeadController extends Controller
{
    private LeadService $leadService;

    public function __construct(LeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    /**
     * Get customer list
     * @param LeadListRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(LeadListRequest $request): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Lead::class);

        # Get Data
        $leads = $this->leadService->getLeads($request);

        # Transform data
        $leads = LeadResource::collection($leads);

        # Build collection response
        $leads = $this->collectionResponse($leads);

        # Return response
        return Response::json([
            'data' => $leads
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Get lead
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show($id): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Lead::class);

        # Get data
        $lead = $this->leadService->getLead($id);

        if (!$lead) {
            return Response::json([
                'error' => 'Not found.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        # Transform data
        $lead = new LeadResource($lead->load('tags'));

        # Return response
        return Response::json([
            'data' => $lead
        ], ResponseAlias::HTTP_OK);
    }
}
