<?php

namespace Moveon\Customer\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Moveon\Customer\Models\Filter;
use Moveon\Customer\Models\FilterCriteria;

class SegmentationStoreRequest extends FormRequest
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
        $nameFilters = Filter::query()->pluck('name');
        $nameFilters = implode(',', $nameFilters->toArray());

        $labelFilters = Filter::query()->pluck('label');
        $labelFilters = implode(',', $labelFilters->toArray());

        $parentCriterias = FilterCriteria::query()->whereNull('parent_id')->pluck('name');
        $parentCriterias = implode(',', $parentCriterias->toArray());

        $childrenCriterias = FilterCriteria::query()->whereNotNull('parent_id')->pluck('name');
        $childrenCriterias = implode(',', $childrenCriterias->toArray());


        return [
            'segmentation_name'                     => 'required|string|unique:user_segmentations,name',
            'name'                                  => 'required|string|in:' . $nameFilters,
            'label'                                 => 'required|string|in:' . $labelFilters,
            'criteria'                              => 'required|array',
            'criteria.name'                         => 'required|string|in:' . $parentCriterias,
            'criteria.value'                        => 'nullable',
            'criteria.value_type'                   => 'nullable|string',
            'criteria.label'                        => 'nullable|string',
            'criteria.children'                     => [
                'array',
                Rule::requiredIf(function () {
                    $criteriaName = strtolower($this->input('criteria.name'));
                    return !in_array($criteriaName, ['type', 'has any of these tags', 'does not have any of these tags']);
                })
            ],
            'criteria.children.name'                => [
                Rule::requiredIf(function () {
                    $criteriaName = strtolower($this->input('criteria.name'));
                    return !in_array($criteriaName, ['type', 'has any of these tags', 'does not have any of these tags']);
                }),
                'string',
                'in:'.$childrenCriterias,
            ],
            'criteria.children.value'               => 'nullable|string',
            'criteria.children.value_type'          => 'nullable|string',
            'criteria.children.label'               => 'nullable|string',
            'criteria.children.children'            => [
                'array',
                Rule::requiredIf(function () {
                    $criteriaName = strtolower($this->input('criteria.name'));
                    return !in_array($criteriaName, ['type', 'has any of these tags', 'does not have any of these tags']);
                })
            ],
            'criteria.children.children.name'       => [
                Rule::requiredIf(function () {
                    $criteriaName = strtolower($this->input('criteria.name'));
                    return !in_array($criteriaName, ['type', 'has any of these tags', 'does not have any of these tags']);
                }),
                'string',
                'in:'.$childrenCriterias
            ],
            'criteria.children.children.value'      => [
                'string',
                Rule::requiredIf(function () {
                    $criteriaName = strtolower($this->input('criteria.name'));
                    return !in_array($criteriaName, ['type', 'has any of these tags', 'does not have any of these tags']);
                })
            ],
            'criteria.children.children.value_type' => [
                'string',
                Rule::requiredIf(function () {
                    $criteriaName = strtolower($this->input('criteria.name'));
                    return !in_array($criteriaName, ['type', 'has any of these tags', 'does not have any of these tags']);
                })
            ],
            'criteria.children.children.label'      => 'nullable|string',
        ];
    }
}
