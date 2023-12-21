<?php

namespace Moveon\Setting\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'type'                  => 'required|string',
            'event'                 => 'required|array',
            'event.created_at'      => 'required|date_format:Y-m-d H:i:s',
            'event.customer_id'     => 'required_unless:type,products',

            'event.first_name'      => 'nullable|string|max:55',
            'event.last_name'       => 'nullable|string|max:55',
            'event.name'            => 'nullable|string|max:55',
            'event.gender'          => 'required_if:type,customers|string|max:55',
            'event.email'           => 'required_if:type,customers|email|max:100',
            'event.phone'           => 'required_if:type,customers|string|max:14',
            'event.post_code'       => 'required_if:type,customers|max:25',

            'event.sub_total_price' => 'required_if:type,orders|numeric',
            'event.total_tax'       => 'required_if:type,orders|numeric',
            'event.total_discount'  => 'required_if:type,orders|numeric',
            'event.total_price'     => 'required_if:type,orders|numeric',
            'event.fully_paid'      => 'required_if:type,orders|boolean',

            'event.item_ids'        => 'required_if:type,wish_list,carts|array',

            'event.product_id'      => 'required_if:type,products',
            'event.title'           => 'required_if:type,products|string|max:255',
            'event.min_price'       => 'required_if:type,products|numeric',
            'event.max_price'       => 'required_if:type,products|numeric',
            'event.image_url'       => 'required_if:type,products|string|max:255',
        ];
    }
}
