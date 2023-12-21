<?php

namespace Moveon\EmailTemplate\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Moveon\EmailTemplate\Models\EmailTemplate;

class EmailTemplateUpdateRequest extends FormRequest
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
        $emailId = $this->route('id');
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('email_templates', 'name')
                    ->where(function ($query) {
                        return $query->where('user_id', auth()->user()->id)
                            ->whereNull('deleted_at');
                    })->ignore($emailId)
            ],
            'design' => 'nullable|array',
            'html'   => 'nullable|string',
            'status' => 'nullable|string|in:' . implode(',', EmailTemplate::STATUS),
        ];
    }
}
