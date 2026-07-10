<?php

namespace App\Http\Requests\Area;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', Rule::unique('areas', 'name')->ignore($this->route('id'))],
            'status'  => ['required', 'boolean'],
            'city_id' => ['required', 'exists:cities,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'اسم المنطقه مطلوب',
            'name.unique'      => 'اسم المنطقه موجود من قبل',
            'status.required'  => 'حالة المنطقه مطلوبة',
            'city_id.required' => 'المدينه مطلوبة',
            'city_id.exists'   => 'المدينه غير موجودة',
        ];
    }
}
