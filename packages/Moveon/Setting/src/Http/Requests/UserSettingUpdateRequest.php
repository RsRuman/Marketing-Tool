<?php

namespace Moveon\Setting\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserSettingUpdateRequest extends FormRequest
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
            'first_name' => 'nullable|string',
            'last_name'  => 'nullable|string',
            'user_name'  => 'required|string',
            'email'      => 'required|email|max:55|unique:users,email,' . Auth::user()->id,
        ];
    }
}
