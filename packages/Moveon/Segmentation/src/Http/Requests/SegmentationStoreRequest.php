<?php

namespace Moveon\Segmentation\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Moveon\Segmentation\Models\Filter;
use Moveon\Segmentation\Models\FilterCriteria;

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
        $auth     = Auth::user();
        $originId = $auth->origin->id;

        $filters = $auth->filters()->where('status', 'active')->get();

        $filterIds = $filters->pluck('id')->toArray();
        $types     = $filters->pluck('type')->toArray();
        $keys      = $filters->pluck('key')->toArray();
        $labels    = $filters->pluck('label')->toArray();

        $filterCriteriaKeys = FilterCriteria::query()->whereIn('filter_id', $filterIds)->where('status', 'active')->pluck('key')->toArray();

        $types              = implode(',', $types);
        $keys               = implode(',', $keys);
        $labels             = implode(',', $labels);
        $filterCriteriaKeys = implode(',', $filterCriteriaKeys);

        return [
            'name'                   => 'required|string:unique:user_segmentations,name,NULL,id,user_id,' . $originId,
            'segmentation'           => 'required|array',
            'segmentation.key'       => 'required|in:' . $keys,
            'segmentation.label'     => 'required|in:' . $labels,
            'segmentation.type'      => 'required|string|in:' . $types,
            'criterias'              => 'required|array',
            'criterias.*.is_parent'  => 'required|boolean',
            'criterias.*.key'        => 'required|string|in:' . $filterCriteriaKeys,
            'criterias.*.label'      => 'nullable|string|in:days,times',
            'criterias.*.value'      => 'nullable|string',
            'criterias.*.value_type' => 'nullable|string'
        ];
    }
}
