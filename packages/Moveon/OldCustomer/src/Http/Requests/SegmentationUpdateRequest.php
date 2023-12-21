<?php

namespace Moveon\Customer\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Moveon\Customer\Models\Filter;
use Moveon\Customer\Models\FilterCriteria;
use Moveon\Customer\Models\UserSegmentation;

class SegmentationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $userSegmentationSlug = $this->route('slug');

        $userSegmentationId = UserSegmentation::query()->where('slug', $userSegmentationSlug)->firstOrFail()->id;

        $nameFilters = Filter::query()->pluck('name');
        $nameFilters = implode(',', $nameFilters->toArray());

        $labelFilters = Filter::query()->pluck('label');
        $labelFilters = implode(',', $labelFilters->toArray());

        $parentCriterias = FilterCriteria::query()->whereNull('parent_id')->pluck('name');
        $parentCriterias = implode(',', $parentCriterias->toArray());

        $childrenCriterias = FilterCriteria::query()->whereNotNull('parent_id')->pluck('name');
        $childrenCriterias = implode(',', $childrenCriterias->toArray());


        return [
            'segmentation_name'                     => 'required|string|unique:user_segmentations,name,' . $userSegmentationId,
            'name'                                  => 'required|string|in:' . $nameFilters,
            'label'                                 => 'required|string|in:' . $labelFilters,
            'criteria'                              => 'required|array',
            'criteria.name'                         => 'required|string|in:' . $parentCriterias,
            'criteria.value'                        => 'nullable|string',
            'criteria.value_type'                   => 'nullable|string',
            'criteria.label'                        => 'nullable|string',
            'criteria.children'                     => 'required|array',
            'criteria.children.name'                => 'required|string|in:' . $childrenCriterias,
            'criteria.children.value'               => 'nullable|string',
            'criteria.children.value_type'          => 'nullable|string',
            'criteria.children.label'               => 'nullable|string',
            'criteria.children.children'            => 'required|array',
            'criteria.children.children.name'       => 'required|string|in:' . $childrenCriterias,
            'criteria.children.children.value'      => 'required|string',
            'criteria.children.children.value_type' => 'required|string',
            'criteria.children.children.label'      => 'nullable|string',
        ];
    }
}
