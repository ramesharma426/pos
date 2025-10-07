<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

//use Illuminate\Foundation\Http\FormRequest;

class ProductStockUpdateRequest extends BaseFormRequest
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
            'id' => ['required'],
            //'product_id' => ['required'],
            'stock' => ['required', 'numeric'],
            'operation' => ['required',Rule::in('add','sub'),], //add or sub
        ];
    }
}
