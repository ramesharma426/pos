<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SalesSearchRequest extends BaseFormRequest
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['']
        ];
    }
}
