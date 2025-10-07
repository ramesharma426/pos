<?php

namespace App\Http\Requests;


class OrderItemStoreRequest extends BaseFormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'product_variant_id' => ['required'],
            'order_id' => ['required'],
            'quantity' => ['required'],
            'delivered_at' => ['required'],
        ];
    }
}
