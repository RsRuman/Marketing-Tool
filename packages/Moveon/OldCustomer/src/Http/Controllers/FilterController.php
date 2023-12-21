<?php

namespace Moveon\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Moveon\Customer\Http\Resources\FilterResource;
use Moveon\Customer\Services\FilterService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FilterController extends Controller
{
    private FilterService $filterService;

    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    public function index(): JsonResponse
    {
        $filters = $this->filterService->getAllFilters();

        $filters = FilterResource::collection($filters);

        return Response::json([
            'data' => $filters
        ], ResponseAlias::HTTP_OK);
    }
}
