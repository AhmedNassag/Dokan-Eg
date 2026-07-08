<?php

namespace App\Http\Requests\Brand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
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
                Rule::unique('brands', 'code')->ignore($this->route('id')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الماركه مطلوب',
            'code.required' => 'كود الماركه مطلوب',
            'code.unique' => 'كود الماركه موجود من قبل',
        ];
    }
}
