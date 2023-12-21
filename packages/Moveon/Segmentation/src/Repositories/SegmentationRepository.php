<?php

namespace Moveon\Segmentation\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Moveon\Segmentation\Models\Segmentation;
use Moveon\Segmentation\Models\UserSegmentation;

class SegmentationRepository
{
    public function index($request): LengthAwarePaginator
    {
        $originId = Auth::user()->origin->id;
        $perPage  = $request->input('per_page', 10);

        return UserSegmentation::query()
            ->where('user_id', $originId)
            ->where('status', UserSegmentation::STATUS_ACTIVE)
            ->with(['segmentations' => function ($query) {
                $query->with('segmentationCriterias');
            }])
            ->paginate($perPage);
    }

    public function find($id)
    {
        $origin = Auth::user()->origin;
        return $origin
            ->userSegmentations()
            ->where('id', $id)
            ->where('status', UserSegmentation::STATUS_ACTIVE)
            ->first();
    }

    public function create($data)
    {
        return UserSegmentation::create($data);
    }

    public function createSegmentation($userSegmentation, $data)
    {
        return $userSegmentation->segmentations()->create($data);
    }

    public function createSegmentationCriteria($segmentation, $data)
    {
        return $segmentation->segmentationCriterias()->create($data);
    }

    public function update($userSegmentation, $data)
    {
        return $userSegmentation->update($data);
    }

    public function updateSegmentation($segmentation, $data)
    {
        return $segmentation->update($data);
    }

    public function updateSegmentationCriteria($criteria, $data)
    {
        return $criteria->update($data);
    }

    public function deleteSegmentationCriteria($criteria)
    {
        return $criteria->delete();
    }

    public function delete($segmentation) {
        return $segmentation->delete();
    }
}
