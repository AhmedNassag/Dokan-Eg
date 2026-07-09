<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255|unique:roles,name,' . $this->route('id'),
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'اسم الدور مطلوب',
            'name.unique'          => 'اسم الدور موجود من قبل',
            'permissions.required' => 'الصلاحيات مطلوبة',
            'permissions.array'    => 'الصلاحيات يجب أن تكون مصفوفة',
            'permissions.*.exists' => 'الصلاحية غير موجودة',
        ];
    }
}
