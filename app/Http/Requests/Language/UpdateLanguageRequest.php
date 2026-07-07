<?php

namespace App\Http\Requests\Language;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLanguageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'code' => ['required', 'string', 'max:10', Rule::unique('languages', 'code')->ignore($this->route('id'))],
            'direction' => ['required', 'in:ltr,rtl'],
            'status' => ['required', 'boolean'],
        ];
    }
}
