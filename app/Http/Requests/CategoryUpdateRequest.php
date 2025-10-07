<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryUpdateRequest extends BaseFormRequest
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
            'name' => ['required', Rule::unique('categories', 'name')->ignore($this->input('id'))]
        ];
    }

    public function passedValidation()
    {
        return $this->safe()->merge([
            //'title' => ucfirst($this->title),
            //trim white space and change string to title case
            'name' => preg_replace('/[\s$@_*]+/', ' ', Str::title($this->name)),
        ]);
    }
}
