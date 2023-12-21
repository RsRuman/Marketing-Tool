<?php
namespace Moveon\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Moveon\Customer\Models\Product;
use Moveon\Product\Http\Resources\ProductResource;
use Moveon\Product\Services\ProductService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * List of products
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(Request $request): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Product::class);

        $products = $this->productService->getProducts($request);

        $products = ProductResource::collection($products);

        $products = $this->collectionResponse($products);

        return Response::json([
            'data' => $products
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Show a product
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show($id): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Product::class);

        $product = $this->productService->getProduct($id);

        if (!$product) {
            return Response::json([
                'error' => 'Product not found.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        $product = new ProductResource($product);

        return Response::json([
            'data' => $product
        ], ResponseAlias::HTTP_OK);
    }
}
