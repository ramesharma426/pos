<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductVariantStoreRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $max_file_size = config('app.max_file_size');
        return [
            'name' => ['required', 'unique:product_variants'],
            'rate' => ['required', 'numeric', 'gte:1', 'lte:1000000'],
            'product_id' => ['required'],
            'quantity' => ['required', 'numeric'],
            'attachment' => ['required','file','mimes:jpg,jpeg,bmp,webp,png,heic',"max:$max_file_size"],
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
