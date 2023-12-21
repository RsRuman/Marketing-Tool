<?php

namespace Moveon\Customer\Repositories;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Moveon\Customer\Models\SegmentationCriteria;
use Moveon\Customer\Models\UserSegmentation;

class SegmentationRepository
{
    public function all($request): LengthAwarePaginator
    {
        $perPage = $request->input('per_page', 10);

        return UserSegmentation::query()->where('user_id', Auth::user()->origin->id)->paginate($perPage);
    }

    public function find($slug): object|null
    {
        return UserSegmentation::query()->where('slug', $slug)->where('user_id', Auth::user()->origin_id)->first();
    }

    public function create($userSegmentation, $data)
    {
        return $userSegmentation->segmentations()->create($data);
    }

    public function createUserSegmentation($data)
    {
        return UserSegmentation::create($data);
    }

    /**
     * @throws Exception
     */
    public function createCriteria($segmentation, $data)
    {
        $data   = $data['criteria'];
        $parent = $segmentation->segmentationCriteria()->create([
            'name'       => $data['name'],
            'value'      => $data['value'],
            'value_type' => $data['value_type'],
            'label'      => $data['label'],
        ]);

        if (!$parent) {
            throw new Exception('Could not create parent.');
        }

        if (!empty($data['children'])) {
            $children = SegmentationCriteria::create([
                'parent_id'  => $parent->id,
                'name'       => $data['children']['name'],
                'value'      => $data['children']['value'],
                'value_type' => $data['children']['value_type'],
                'label'      => $data['children']['label'],
            ]);

            if ($children && !empty($data['children']['children'])) {
                SegmentationCriteria::create([
                    'parent_id'  => $children->id,
                    'name'       => $data['children']['children']['name'],
                    'value'      => $data['children']['children']['value'],
                    'value_type' => $data['children']['children']['value_type'],
                    'label'      => $data['children']['children']['label'],
                ]);
            }
        }
        return $parent;
    }

    public function updateUserSegmentation($data, $userSegmentation)
    {
        return $userSegmentation->update($data);
    }

    public function update($userSegmentation, $data)
    {
        return $userSegmentation->segmentations()->update($data);
    }

    /**
     * Update customer segmentation criteria
     * @throws Exception
     */
    public function updateCriteria($segmentations, $data): bool
    {
        foreach ($segmentations as $segmentation) {
            $data   = $data['criteria'];
            $parent = $segmentation->segmentationCriteria()->update([
                'name'       => $data['name'],
                'value'      => $data['value'],
                'value_type' => $data['value_type'],
                'label'      => $data['label'],
            ]);

            if (!$parent) {
                throw new Exception('Could not update parent.');
            }

            if (!empty($data['children']) && $segmentation->segmentationCriteria->children) {
                $childrenU = $segmentation->segmentationCriteria->children->update([
                    'name'       => $data['children']['name'],
                    'value'      => $data['children']['value'],
                    'value_type' => $data['children']['value_type'],
                    'label'      => $data['children']['label'],
                ]);

                if (!$childrenU) {
                    throw new Exception('Could not update children.');
                }

                if ($segmentation->segmentationCriteria->children->children && !empty($data['children']['children'])) {
                    $grandChildrenU = $segmentation->segmentationCriteria->children->children->update([
                        'name'       => $data['children']['children']['name'],
                        'value'      => $data['children']['children']['value'],
                        'value_type' => $data['children']['children']['value_type'],
                        'label'      => $data['children']['children']['label'],
                    ]);

                    if (!$grandChildrenU) {
                        throw new Exception('Could not update grand children.');
                    }
                }
            }
        }

        return true;
    }

    public function delete($userSegment): bool
    {
        foreach ($userSegment->segmentations as $segmentation) {
            $segmentation?->segmentationCriteria?->children?->children->delete();
            $segmentation?->segmentationCriteria?->children?->delete();
            $segmentation?->segmentationCriteria?->delete();
            $segmentation->delete();
        }
        return $userSegment->delete();
    }
}
