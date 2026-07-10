<?php

namespace App\Http\Requests\Branch;

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
            'name'    => 'required|string',
            'status'  => 'required|boolean',
            'code'    => 'required|string|unique:branches',
            'mobile'  => 'required|string|max:192',
            'address' => 'nullable|string',
            'area_id' => 'required|exists:areas,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'اسم الفرع مطلوب',
            'code.required'    => 'كود الفرع مطلوب',
            'code.unique'      => 'كود الفرع موجود من قبل',
            'mobile.required'  => 'رقم الجوال مطلوب',
            'area_id.required' => 'المنطقه مطلوبة',
            'area_id.exists'   => 'المنطقه غير موجودة',
        ];
    }
}
