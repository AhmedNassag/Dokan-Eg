<?php

namespace App\Http\Requests\Language;

use Illuminate\Foundation\Http\FormRequest;

class StoreLanguageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'code' => 'required|string|max:10|unique:languages,code',
            'direction' => 'required|in:ltr,rtl',
            'status' => 'required|boolean',
        ];
    }
}
