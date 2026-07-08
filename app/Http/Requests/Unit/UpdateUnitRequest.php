<?php

namespace App\Http\Requests\Unit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'status' => ['required', 'boolean'],
            'code' => [
                'required',
                'string',
                Rule::unique('units', 'code')->ignore($this->route('id')),
            ],
            'short_name' => ['required', 'string', 'max:192'],
            'base_unit' => ['nullable', 'exists:units,id'],
            'operator' => ['nullable', 'string', 'in:*,/,+,-', 'max:10'],
            'operator_value' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الوحده مطلوب',
            'code.required' => 'كود الوحده مطلوب',
            'code.unique' => 'كود الوحده موجود من قبل',
            'short_name.required' => 'الاختصار مطلوب',
            'base_unit.exists' => 'الوحده الاساسيه غير موجوده',
        ];
    }
}
