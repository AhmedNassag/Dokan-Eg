<?php

namespace App\Http\Requests\Translation;

use Illuminate\Foundation\Http\FormRequest;

class StoreTranslationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'language_id' => 'required|exists:languages,id',
            'group' => 'nullable|string',
            'key' => 'required|string',
            'value' => 'nullable|string',
        ];
    }
}
