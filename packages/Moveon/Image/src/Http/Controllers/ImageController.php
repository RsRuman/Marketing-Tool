<?php

namespace Moveon\Image\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Moveon\Image\Http\Requests\ImageListRequest;
use Moveon\Image\Http\Requests\ImageStoreRequest;
use Moveon\Image\Http\Requests\ImageUpdateRequest;
use Moveon\Image\Http\Resources\ImageResource;
use Moveon\Image\Models\Category;
use Moveon\Image\Models\Image;
use Moveon\Image\Services\ImageService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ImageController extends Controller
{
    private ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * List of image
     * @param ImageListRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(ImageListRequest $request): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Image::class);

        # Get data
        $images = $this->imageService->getImages($request);

        # Transform image
        $images = ImageResource::collection($images);

        # Build collection response for pagination
        $response = $this->collectionResponse($images);

        # Return response
        return Response::json($response, ResponseAlias::HTTP_OK);
    }

    /**
     * Store image
     * @param ImageStoreRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(ImageStoreRequest $request): JsonResponse
    {
        # Check authorization
        $this->authorize('create', Image::class);

        # Create image
        $image = $this->imageService->createImage($request);

        if (!$image) {
            # Return response
            return Response::json([
                'error' => 'Could not create image. Please try later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        # Load categories
        $image = $image->load('categories', 'createdBy', 'updatedBy');

        # Transform image
        $image = new ImageResource($image);

        # Return response
        return Response::json([
            'data' => $image
        ], ResponseAlias::HTTP_CREATED);
    }

    /**
     * Show image
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        # Get data
        $image = $this->imageService->getImage($id);

        # Check data
        if (!$image) {
            return Response::json([
                'error' => 'Not found!'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        # Transform image
        $image = new ImageResource($image);

        # Return response
        return Response::json([
            'data' => $image
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Update image
     * @param ImageUpdateRequest $request
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(ImageUpdateRequest $request, $id): JsonResponse
    {
        # Check authorization
        $this->authorize('edit', Image::class);


        $imageU = $this->imageService->updateImage($request, $id);

        # If not update
        if (!$imageU) {
            # Return response
            return Response::json([
                'error' => 'Could not update image. Please try later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $imageU = $imageU->load('categories', 'createdBy', 'updatedBy');

        # Transform image
        $imageU = new ImageResource($imageU->load('categories'));

        # Return response
        return Response::json([
            'data' => $imageU
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Delete image
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy($id): JsonResponse
    {
        # Check authorization
        $this->authorize('edit', Image::class);

        $imageD = $this->imageService->deleteImage($id);

        # If not delete
        if (!$imageD) {
            # Return response
            return Response::json([
                'error' => 'Could not delete image. Please try later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        # Return response
        return Response::json([
            'message' => 'Image deleted successfully.'
        ], ResponseAlias::HTTP_OK);
    }
}
