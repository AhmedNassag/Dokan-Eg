<?php

namespace App\Http\Requests\Translation;

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
            'language_id' => 'required|exists:languages,id',
            'group'       => 'nullable|string',
            'key'         => 'required|string',
            'value'       => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'language_id.required' => 'لغة الترجمة مطلوبة',
            'language_id.exists'   => 'لغة الترجمة غير موجودة',
            'key.required'         => 'مفتاح الترجمة مطلوب',
            'key.string'           => 'مفتاح الترجمة يجب أن يكون نصًا',
            'value.string'         => 'قيمة الترجمة يجب أن تكون نصًا',
        ];
    }
}
