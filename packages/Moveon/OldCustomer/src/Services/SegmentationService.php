<?php

namespace Moveon\Customer\Services;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Moveon\Customer\Repositories\SegmentationRepository;

class SegmentationService
{
    private SegmentationRepository $segmentationRepository;

    public function __construct(SegmentationRepository $segmentationRepository)
    {
        $this->segmentationRepository = $segmentationRepository;
    }

    public function getUserSegmentations($request): LengthAwarePaginator
    {
        return $this->segmentationRepository->all($request);
    }

    /**
     * Get a segmentation
     * @param $slug
     * @return object|null
     */
    public function getUserSegmentation($slug): object|null
    {
        return $this->segmentationRepository->find($slug);
    }

    /**
     * @throws Exception
     */
    public function createSegmentation($request)
    {
        # Sanitize data for user segmentation
        $dataUserSegmentation ['name']         = $request->input('segmentation_name');
        $dataUserSegmentation ['slug']         = Str::slug($request->input('segmentation_name'));
        $dataUserSegmentation['user_id']       = $request->user()->origin()->first()->id;
        $dataUserSegmentation['created_by_id'] = $request->user()->id;

        $userSegmentation = $this->segmentationRepository->createUserSegmentation($dataUserSegmentation);

        if (!$userSegmentation) {
            throw new Exception('Could not create user segmentation');
        }

        # Sanitize data for segmentation
        $dataSegmentation = $request->only('name', 'label');
        $segmentation     = $this->segmentationRepository->create($userSegmentation, $dataSegmentation);

        if (!$segmentation) {
            throw new Exception('Could not create segmentation.');
        }

        # Sanitize data for criteria
        $dataCriteria = $request->only('criteria');

        $segmentationCriteria = $this->segmentationRepository->createCriteria($segmentation, $dataCriteria);

        if (!$segmentationCriteria) {
            throw new Exception('Could not create segmentation criteria');
        }

        return $userSegmentation;
    }

    /**
     * Update user segmentation
     * @throws Exception
     */
    public function updateUserSegmentation($request, $slug): Model|bool
    {
        # Find user segmentation
        $userSegmentation = $this->segmentationRepository->find($slug);

        if (!$userSegmentation) {
            return false;
        }

        # Sanitize data for user segmentation
        $dataUserSegmentation ['name']         = $request->input('segmentation_name');
        $dataUserSegmentation ['slug']         = Str::slug($request->input('segmentation_name'));
        $dataUserSegmentation['updated_by_id'] = $request->user()->id;

        $userSegmentationU = $this->segmentationRepository->updateUserSegmentation($dataUserSegmentation, $userSegmentation);

        if (!$userSegmentationU) {
            throw new Exception('Could not update user segmentation');
        }

        # Sanitize data for segmentation
        $dataSegmentation = $request->only('name', 'label');
        $segmentationU    = $this->segmentationRepository->update($userSegmentation, $dataSegmentation);

        if (!$segmentationU) {
            throw new Exception('Could not update segmentation.');
        }

        # Sanitize data for criteria
        $dataCriteria = $request->only('criteria');

        $segmentationCriteria = $this->segmentationRepository->updateCriteria($userSegmentation->segmentations, $dataCriteria);

        if (!$segmentationCriteria) {
            throw new Exception('Could not update segmentation criteria');
        }

        return $userSegmentation->fresh();
    }

    /**
     * Delete user segmentation
     * @param $slug
     * @return bool
     */
    public function deleteUserSegmentation($slug): bool
    {
        $userSegment = $this->segmentationRepository->find($slug);

        if (!$userSegment) {
            return false;
        }

        return $this->segmentationRepository->delete($userSegment);
    }
}
