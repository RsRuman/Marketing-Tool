<?php

namespace Moveon\Segmentation\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Moveon\Segmentation\Http\Requests\SegmentationListRequest;
use Moveon\Segmentation\Http\Requests\SegmentationStoreRequest;
use Moveon\Segmentation\Http\Requests\SegmentationUpdateRequest;
use Moveon\Segmentation\Http\Resources\SegmentationResource;
use Moveon\Segmentation\Http\Resources\UserSegmentationResource;
use Moveon\Segmentation\Models\Segmentation;
use Moveon\Segmentation\Services\SegmentationService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SegmentationController extends Controller
{
    private SegmentationService $segmentationService;

    public function __construct(SegmentationService $segmentationService)
    {
        $this->segmentationService = $segmentationService;
    }

    /**
     * Get list of segmentation
     * @param SegmentationListRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(SegmentationListRequest $request): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Segmentation::class);

        $segmentations = $this->segmentationService->getSegmentations($request);

        $segmentations = UserSegmentationResource::collection($segmentations);

        return Response::json([
            'data' => $segmentations
        ], ResponseAlias::HTTP_OK);

    }

    /**
     * Show a segmentation
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show($id): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Segmentation::class);

        $segmentation = $this->segmentationService->getSegmentation($id);

        if (!$segmentation) {
            return Response::json([
                'error' => 'Not found.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        $segmentation = new UserSegmentationResource($segmentation->load('segmentations.segmentationCriterias'));

        return Response::json([
            'data' => $segmentation
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Create segmentation
     * @param SegmentationStoreRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(SegmentationStoreRequest $request): JsonResponse
    {
        # Check authorization
        $this->authorize('create', Segmentation::class);

        try {
            DB::beginTransaction();

            $this->segmentationService->createSegmentation($request);

            DB::commit();

            return Response::json([
                'message' => 'Segmentation created successfully.'
            ], ResponseAlias::HTTP_CREATED);

        } catch (Exception $ex) {
            DB::rollBack();
            return Response::json([
                'error' => 'Something goes wrong. Please try later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Update segmentation
     * @param SegmentationUpdateRequest $request
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(SegmentationUpdateRequest $request, $id): JsonResponse
    {
        # Check authorization
        $this->authorize('edit', Segmentation::class);

        $userSegmentation = $this->segmentationService->getSegmentation($id);

        if (!$userSegmentation) {
            return Response::json([
                'error' => 'Not found.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        try {
            DB::beginTransaction();

            $this->segmentationService->updateSegmentation($userSegmentation, $request);

            DB::commit();

            return Response::json([
                'message' => 'Segmentation updated successfully.'
            ], ResponseAlias::HTTP_OK);

        } catch (Exception $ex) {
            DB::rollBack();
            return Response::json([
                'error' => 'Could not update segmentation. Please try later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Delete segmentation
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function delete($id): JsonResponse
    {
        # Check authorization
        $this->authorize('delete', Segmentation::class);
        # Find segmentation
        $segmentation = $this->segmentationService->getSegmentation($id);

        if (!$segmentation) {
            return Response::json([
                'error' => 'Not found.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        $segmentationD = $this->segmentationService->deleteSegmentation($segmentation);

        if (!$segmentationD) {
            return Response::json([
               'error' => 'Could not delete segmentation.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        # Return response
        return Response::json([
            'message' => 'Segmentation deleted successfully.'
        ], ResponseAlias::HTTP_OK);
    }
}
