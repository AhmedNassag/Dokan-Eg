<?php

namespace App\Http\Requests\City;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('cities', 'name')->ignore($this->route('id')),
            ],
            'status' => ['required', 'boolean'],
            'country_id' => ['required', 'exists:countries,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المدينه مطلوب',
            'name.unique' => 'اسم المدينه موجود من قبل',
            'status.required' => 'حالة المدينه مطلوبة',
            'country_id.required' => 'الدوله مطلوبة',
            'country_id.exists' => 'الدوله غير موجودة',
        ];
    }
}
