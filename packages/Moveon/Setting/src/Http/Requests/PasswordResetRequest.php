<?php

namespace Moveon\Setting\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
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
            'password'              => 'required|string|min:8|max:55|confirmed',
            'password_confirmation' => 'required|string|min:8|max:55',
            'otp'                   => 'required|string|min:4|max:4'
        ];
    }
}
