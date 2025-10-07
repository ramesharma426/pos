<?php

namespace App\Http\Requests;

//use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserEmailUpdateRequest extends BaseFormRequest
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
            'email'=>['required', 'email', 'max:60', Rule::unique('users', 'email')->ignore($this->input('id'))],
        ];
    }

    public function passedValidation()
    {
        return $this->safe()->merge([
            'email' => Str::lower($this->email),

        ]);
    }
}
