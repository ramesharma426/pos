<?php

namespace App\Http\Requests;

//use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UserStoreRequest extends BaseFormRequest
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
            'name'=>'required|max:255',
            'email'=>'required|email|unique:users|max:255',
            'password'=>'required|confirmed|min:8',
            'role_id' => 'required',
        ];
    }

    public function passedValidation()
    {
        return $this->safe()->merge([
            'name' => preg_replace('/[\s$@_*]+/', ' ', Str::title($this->name)),
            'email' => Str::lower($this->email),

        ]);
    }
}
