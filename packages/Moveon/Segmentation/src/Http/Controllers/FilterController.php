<?php

namespace Moveon\Segmentation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Moveon\Segmentation\Http\Resources\FilterResource;
use Moveon\Segmentation\Services\FilterService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FilterController extends Controller
{
    private FilterService $filterService;

    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    /**
     * Get all filters
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $filters = $this->filterService->getFilters();

        foreach ($filters as $filter) {
            if (count($filter->filterCriterias) <= 0) {
                $filter->filterCriterias = $this->filterService->getGlobalFilters();
            }
        }

        # Transform data
        $filters = FilterResource::collection($filters);

        # Return response
        return Response::json([
            'data' => $filters
        ], ResponseAlias::HTTP_OK);
    }
}
