<?php

namespace App\Http\Requests\Language;

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
            'name'      => ['required', 'string'],
            'code'      => ['required', 'string', 'max:10', Rule::unique('languages', 'code')->ignore($this->route('id'))],
            'direction' => ['required', 'in:ltr,rtl'],
            'status'    => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'اسم اللغة مطلوب ',
            'code.required'      => 'رمز اللغة مطلوب ',
            'code.max'           => 'رمز اللغة لا يجب ان يزيد عن 10 احرف ',
            'code.unique'        => 'رمز اللغة موجود من قبل',
            'direction.required' => 'اتجاه اللغة مطلوب ',
            'direction.in'       => 'اتجاه اللغة غير صحيح',
            'status.required'    => 'حالة اللغة مطلوبة ',
        ];
    }
}
