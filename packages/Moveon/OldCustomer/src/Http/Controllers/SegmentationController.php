<?php

namespace Moveon\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Moveon\Customer\Http\Requests\SegmentationStoreRequest;
use Moveon\Customer\Http\Requests\SegmentationUpdateRequest;
use Moveon\Customer\Http\Resources\CustomerResource;
use Moveon\Customer\Http\Resources\UserSegmentationResource;
use Moveon\Customer\Models\Segmentation;
use Moveon\Customer\Services\SegmentationService;
use Moveon\Customer\Traits\QueryGeneratorTrait;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SegmentationController extends Controller
{
    use QueryGeneratorTrait;

    private SegmentationService $segmentationService;

    public function __construct(SegmentationService $segmentationService)
    {
        $this->segmentationService = $segmentationService;
    }

    /**
     * List of segmentations
     * @throws AuthorizationException
     */
    public function index(Request $request): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Segmentation::class);

        $userSegmentations = $this->segmentationService->getUserSegmentations($request);

        $userSegmentations = UserSegmentationResource::collection($userSegmentations);

        $userSegmentations = $this->collectionResponse($userSegmentations);

        return Response::json([
            'data' => $userSegmentations
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Show segmentation
     * @throws AuthorizationException
     * @throws Exception
     */
    public function show(Request $request, $slug): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Segmentation::class);

        $userSegmentation = $this->segmentationService->getUserSegmentation($slug);

        if (!$userSegmentation) {
            return Response::json([
                'error' => 'Not found.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        $userSegmentation = $userSegmentation->load('segmentations.segmentationCriteria.children.children');

        $perPage = $request->input('per_page', 10);

        # Generate query
        $customers = $this->query($userSegmentation->segmentations, $perPage);

        # Transform customers
        $customers = CustomerResource::collection($customers);

        if (count($customers) > 0) {
            $customers = $this->collectionResponse($customers);
        }

        $userSegmentation = new UserSegmentationResource($userSegmentation);

        return Response::json([
            'customerSegmentations' => $userSegmentation,
            'customers'             => $customers
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Create segmentation
     * @throws AuthorizationException
     * @throws Exception
     */
    public function store(SegmentationStoreRequest $request): JsonResponse
    {
        # Check authorization
        $this->authorize('create', Segmentation::class);

        try {
            DB::beginTransaction();

            $userSegmentation = $this->segmentationService->createSegmentation($request);

            if (!$userSegmentation) {
                throw new Exception('Could not create segmentation.');
            }

            DB::commit();

            $userSegmentation = new UserSegmentationResource($userSegmentation);

            return Response::json([
                'data' => $userSegmentation
            ], ResponseAlias::HTTP_CREATED);

        } catch (Exception $ex) {
            DB::rollBack();

            return Response::json([
                'error' => $ex->getMessage()
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Update user segmentation with criteria
     * @throws AuthorizationException
     */
    public function update(SegmentationUpdateRequest $request, $slug): JsonResponse
    {
        # Check authorization
        $this->authorize('edit', Segmentation::class);

        try {
            DB::beginTransaction();

            $userSegmentationU = $this->segmentationService->updateUserSegmentation($request, $slug);

            if (!$userSegmentationU) {
                throw new Exception('Could not update segmentation.');
            }

            DB::commit();

            $userSegmentation = new UserSegmentationResource($userSegmentationU);

            return Response::json([
                'data' => $userSegmentation
            ], ResponseAlias::HTTP_CREATED);

        } catch (Exception $ex) {
            DB::rollBack();

            return Response::json([
                'error' => $ex->getMessage()
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Delete user segmentation
     * @throws AuthorizationException
     */
    public function destroy($slug): JsonResponse
    {
        # Check authorize
        $this->authorize('delete', Segmentation::class);

        $userSegment = $this->segmentationService->deleteUserSegmentation($slug);

        if (!$userSegment) {
            return Response::json([
                'error' => 'Could not delete segment. Please try later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => 'Segmentation deleted successfully.'
        ], ResponseAlias::HTTP_OK);
    }
}
