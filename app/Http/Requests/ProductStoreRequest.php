<?php

namespace App\Http\Requests;


use Illuminate\Support\Str;

class ProductStoreRequest extends BaseFormRequest
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
        //$max_file_size = config('app.max_file_size');
        return [
            'name' => ['required', 'unique:products'],
            //'rate' => ['required', 'numeric', 'gte:1', 'lte:1000000'],
            'unit_id' => ['required'],
            //'product_code' => ['required','unique:products'],
            'category_id' => ['required'],
            //'attachment' => ['required','file','mimes:jpg,jpeg,bmp,webp,png',"max:$max_file_size"],
        ];
    }

    public function passedValidation()
    {
        return $this->safe()->merge([
            //trim white space and change string to title case
            'name' => preg_replace('/[\s$@_*]+/', ' ', Str::title($this->name)),
        ]);
    }
}
