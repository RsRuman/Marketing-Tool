<?php

namespace Moveon\Image\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Moveon\Image\Http\Resources\CategoryResource;
use Moveon\Image\Services\CategoryService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CategoryController extends Controller
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * List of categories
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        # Get data
        $categories = $this->categoryService->getCategories();

        # Transform data
        $categories = CategoryResource::collection($categories);

        # Return response
        return Response::json([
            'data' => $categories
        ], ResponseAlias::HTTP_OK);

    }
}
