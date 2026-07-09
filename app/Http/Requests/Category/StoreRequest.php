<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,id',
            'is_active'   => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'اسم الفئة مطلوب',
            'name.unique'      => 'اسم الفئة موجود من قبل',
            'description.required' => 'الوصف مطلوب',
            'parent_id.required' => 'الفئة الأم مطلوبة',
            'is_active.required' => 'الحالة مطلوبة',
        ];
    }
}
