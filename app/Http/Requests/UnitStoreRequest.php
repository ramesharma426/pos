<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;

class UnitStoreRequest extends BaseFormRequest
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
            'name' => ['required'],
        ];
    }


    public function passedValidation()
    {
        return $this->safe()->merge([
            //'title' => ucfirst($this->title),
            //trim white space and change string to title case
            'name' => preg_replace('/[\s$@_*]+/', ' ', Str::lower($this->name)),
        ]);
    }
}
