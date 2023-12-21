<?php

namespace Moveon\User\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
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
        $roleId = $this->route('id');

        return [
            'name' => 'required|max:55|unique:roles,name,'.$roleId.',id,user_id,' . auth()->user()->id,
        ];
    }
}
