<?php

namespace Moveon\User\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignRoleRequest extends FormRequest
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
            'roleIds'   => 'required|array',
            'roleIds.*' => Rule::exists('roles', 'id')->where(function ($query) {
                $query->where('user_id', auth()->user()->id);
            })
        ];
    }
}
