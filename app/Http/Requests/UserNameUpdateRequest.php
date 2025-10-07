<?php

namespace App\Http\Requests;

//use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UserNameUpdateRequest extends BaseFormRequest
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
            'id' => 'required',
            'name' => 'required',
        ];
    }

    public function passedValidation()
    {
        return $this->safe()->merge([
            'name' => preg_replace('/[\s$@_*]+/', ' ', Str::title($this->name)),

        ]);
    }
}
