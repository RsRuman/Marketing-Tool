<?php

namespace Moveon\User\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'first_name'            => 'nullable|string|min:2|max:55',
            'last_name'             => 'nullable|string|min:2|max:55',
            'user_name'             => 'nullable|string|min:2|max:55',
            'email'                 => 'required|email|max:55|unique:users,email,' . $id,
            'password'              => 'required|string|min:8|max:55|confirmed',
            'password_confirmation' => 'required|string|min:8|max:55',
        ];
    }
}
