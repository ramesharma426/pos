<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;

class OrderUpdateRequest extends BaseFormRequest
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
            'customer_name' => ['nullable'],
            //'customer_address' => ['nullable'],
            //'customer_phone_number' => ['nullable'],
            'table_id' => ['required']
        ];
    }

    public function passedValidation()
    {
        return $this->safe()->merge([
            //trim white space and change string to title case
            'customer_name' => $this->customer_name != '' ? preg_replace('/[\s$@_*]+/', ' ', Str::title($this->customer_name)) : null,
            //'customer_address' => $this->customer_address != '' ? preg_replace('/[\s$@_*]+/', ' ', Str::title($this->customer_address)) : null
        ]);
    }
}
