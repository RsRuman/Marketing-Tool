<?php

namespace Moveon\Image\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Moveon\Image\Models\Category;

class ImageStoreRequest extends FormRequest
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
            'image'        => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories'   => 'nullable|array',
            'categories.*' => 'nullable|integer|in:' . implode(',', Category::all()->pluck('id')->toArray())
        ];
    }
}
