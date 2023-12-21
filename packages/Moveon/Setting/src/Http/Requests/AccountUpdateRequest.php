<?php

namespace Moveon\Setting\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Moveon\Platform\Models\Platform;

class AccountUpdateRequest extends FormRequest
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
        $originId = Auth::user()->origin->id;

        $platform =  Platform::query()->where('user_id', $originId)->first();

        return [
            'company_name' => 'required|string',
            'email'        => 'required|email|unique:platforms,email,' . $platform->id,
            'store'        => 'required|string|unique:platforms,domain,' . $platform->id,
            'website'      => 'required|url|max:55|unique:platforms,website,' . $platform->id,
        ];
    }
}
