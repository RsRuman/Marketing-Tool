<?php

namespace Moveon\Segmentation\Services;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Moveon\Segmentation\Repositories\SegmentationRepository;

class SegmentationService
{
    private SegmentationRepository $segmentationRepository;

    public function __construct(SegmentationRepository $segmentationRepository)
    {
        $this->segmentationRepository = $segmentationRepository;
    }

    /**
     * Get segmentations
     * @param $request
     * @return LengthAwarePaginator
     */
    public function getSegmentations($request): LengthAwarePaginator
    {
        return $this->segmentationRepository->index($request);
    }

    /**
     * Get segmentation
     * @param $id
     * @return mixed
     */
    public function getSegmentation($id): mixed
    {
        return $this->segmentationRepository->find($id);
    }

    /**
     * Create segmentation
     * @param $request
     * @return mixed
     * @throws Exception
     */
    public function createSegmentation($request): mixed
    {
        $authUser   = Auth::user();
        $originId   = $authUser->origin->id;
        $authUserId = $authUser->id;

        $userSegmentationData['user_id']    = $originId;
        $userSegmentationData['created_by'] = $authUserId;
        $userSegmentationData['name']       = $request->input('name');
        $userSegmentationData['slug']       = Str::slug($request->input('name'));

        $userSegmentation = $this->segmentationRepository->create($userSegmentationData);

        if (!$userSegmentation) {
            throw new Exception('Could not create user segmentation');
        }

        $segmentationData = $request->input('segmentation');

        $segmentation = $this->segmentationRepository->createSegmentation($userSegmentation, $segmentationData);


        if (!$segmentation) {
            throw new Exception('Could not create segmentation');
        }

        $criterias = $request->input('criterias');

        foreach ($criterias as $criteria) {
            $segmentationCriteria = $this->segmentationRepository->createSegmentationCriteria($segmentation, $criteria);
            if (!$segmentationCriteria) {
                throw new Exception('Could not create criteria');
            }
        }

        return $userSegmentation;
    }

    /**
     * Update segmentation
     * @param $userSegmentation
     * @param $request
     * @return bool
     * @throws Exception
     */
    public function updateSegmentation($userSegmentation, $request): bool
    {
        $authUser = Auth::user();

        $userSegmentationData['updated_by'] = $authUser->id;
        $userSegmentationData['name']       = $request->input('name');
        $userSegmentationData['slug']       = Str::slug($request->input('name'));

        $userSegmentationU = $this->segmentationRepository->update($userSegmentation, $userSegmentationData);

        if (!$userSegmentationU) {
            throw new Exception('Could not update user segmentation.');
        }

        $segmentationData = $request->input('segmentation');

        $segmentationU = $this->segmentationRepository->updateSegmentation($userSegmentation->segmentation, $segmentationData);

        if (!$segmentationU) {
            throw new Exception('Could not update segmentation.');
        }

        $criterias = $request->input('criterias');

        if (count($userSegmentation->segmentation->segmentationCriterias) === 1 && count($criterias) === 1) {
            $segmentationCriteriaU = $this->segmentationRepository->updateSegmentationCriteria($userSegmentation->segmentation->segmentationCriteria, $criterias[0]);

            if (!$segmentationCriteriaU) {
                throw new Exception('Could not update segmentation criteria.');
            }
        }

        if (count($userSegmentation->segmentation->segmentationCriterias) === 2 && count($criterias) === 2) {
            $segmentationCriteriaOneU = $this->segmentationRepository->updateSegmentationCriteria($userSegmentation->segmentation->segmentationCriterias()->first(), $criterias[0]);

            if (!$segmentationCriteriaOneU) {
                throw new Exception('Could not update segmentation criteria.');
            }

            $segmentationCriteriaTwoU = $this->segmentationRepository->updateSegmentationCriteria($userSegmentation->segmentation->segmentationCriterias()->skip(1)->first(), $criterias[1]);

            if (!$segmentationCriteriaTwoU) {
                throw new Exception('Could not update segmentation criteria.');
            }
        }

        if (count($userSegmentation->segmentation->segmentationCriterias) === 1 && count($criterias) === 2) {
            $segmentationCriteriaU = $this->segmentationRepository->updateSegmentationCriteria($userSegmentation->segmentation->segmentationCriterias()->first(), $criterias[0]);

            if (!$segmentationCriteriaU) {
                throw new Exception('Could not update segmentation criteria.');
            }

            $segmentationCriteriaC = $this->segmentationRepository->createSegmentationCriteria($userSegmentation->segmentation, $criterias[0]);

            if (!$segmentationCriteriaC) {
                throw new Exception('Could not create segmentation criteria.');
            }
        }

        if (count($userSegmentation->segmentation->segmentationCriterias) === 2 && count($criterias) === 1) {
            $segmentationCriteriaU = $this->segmentationRepository->updateSegmentationCriteria($userSegmentation->segmentation->segmentationCriterias()->first(), $criterias[0]);

            if (!$segmentationCriteriaU) {
                throw new Exception('Could not update segmentation criteria.');
            }

            $segmentationCriteriaD = $this->segmentationRepository->deleteSegmentationCriteria($userSegmentation->segmentation->segmentationCriterias()->skip(1)->first());

            if (!$segmentationCriteriaD) {
                throw new Exception('Could not delete segmentation criteria.');
            }
        }

        return true;
    }

    /**
     * Delete segmentation
     * @param $segmentation
     * @return mixed
     */
    public function deleteSegmentation($segmentation): mixed
    {
        return $this->segmentationRepository->delete($segmentation);
    }
}
