<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sku'                 => ['required', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($this->route('id'))],
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string|max:255',
            'price'               => 'required|numeric|min:0',
            'stock_quantity'      => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'status'              => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'sku.required'                => 'الSKU مطلوب',
            'sku.unique'                  => 'الSKU مسجل بالفعل',
            'sku.max'                     => 'الSKU يجب أن يكون مكون من 255 حرف أو أقل',
            'name.required'               => 'الاسم مطلوب',
            'description.required'        => 'الوصف مطلوب',
            'price.required'              => 'السعر مطلوب',
            'stock_quantity.required'     => 'الكمية المتوفرة مطلوبة',
            'low_stock_threshold.integer' => ' يجب أن يكون رقمًا صحيحًا',
            'status.required'             => 'الحالة مطلوبة',
            'status.in'                   => 'الحالة غير صالحة',
        ];
    }
}
