<?php

namespace Moveon\Tag\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Moveon\Tag\Models\Tag;

class AttachTagRequest extends FormRequest
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
            'lead_id'   => 'required',
            'tag_ids'   => 'required|array',
            'tag_ids.*' => 'required|in:' . implode(',', Tag::all()->pluck('id')->toArray())
        ];
    }
}
