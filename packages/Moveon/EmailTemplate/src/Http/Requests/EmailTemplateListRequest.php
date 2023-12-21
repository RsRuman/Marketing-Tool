<?php

namespace Moveon\EmailTemplate\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Moveon\EmailTemplate\Models\EmailTemplate;

class EmailTemplateListRequest extends FormRequest
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
        return [
            'name'     => 'nullable|string|max:255',
            'per_page' => 'nullable|integer',
            'sort_by'  => 'nullable|string|in:' . implode(',', EmailTemplate::SORT_BY),
        ];
    }
}
