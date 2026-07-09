<?php

namespace App\Http\Requests\ShippingCompany;

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
            'name'             => ['required', 'string'],
            'code'             => ['required', 'string', Rule::unique('shipping_companies', 'code')->ignore($this->route('id'))],
            'phone'            => ['required', 'string'],
            'status'           => ['required', 'boolean'],
            'prices'           => ['nullable', 'array'],
            'prices.*.city_id' => ['required', 'exists:cities,id'],
            'prices.*.price'   => ['required', 'numeric', 'min:0'],
        ];
    }

    

    public function messages(): array
    {
        return [
            'name.required'             => 'اسم الشركة مطلوب',
            'code.required'             => 'كود الشركة مطلوب',
            'code.unique'               => 'كود الشركة موجود من قبل',
            'phone.required'            => 'رقم الهاتف مطلوب',
            'prices.*.city_id.required' => 'المدينة مطلوبة',
            'prices.*.city_id.exists'   => 'المدينة غير موجودة',
            'prices.*.price.required'   => 'السعر مطلوب',
            'prices.*.price.numeric'    => 'السعر يجب أن يكون رقمًا',
            'prices.*.price.min'        => 'السعر يجب أن يكون أكبر من 0',
        ];
    }
}
