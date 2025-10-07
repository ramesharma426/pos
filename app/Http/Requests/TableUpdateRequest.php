<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;

class TableUpdateRequest extends BaseFormRequest
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
            'number' => ['required', 'string', Rule::unique('tables', 'number')->ignore($this->input('id'))],
            'capacity' => ['required', 'integer', 'gte:1', 'lte:120']
        ];
    }

    public function messages()
    {
        return [
            'number.unique' => 'Table number already taken',
        ];
    }
}
