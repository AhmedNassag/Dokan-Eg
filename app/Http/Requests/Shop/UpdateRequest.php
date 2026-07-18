<?php

namespace App\Http\Requests\Shop;

use App\Enums\ShopFontFamily;
use App\Enums\ShopTheme;
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
        $id = $this->route('id');

        return [
            'user_id'          => ['sometimes', 'exists:users,id', Rule::unique('shops', 'user_id')->ignore($id)],
            'name'             => ['required', 'string', 'max:150'],
            'slug'             => ['required', 'string', 'max:180', Rule::unique('shops', 'slug')->ignore($id)],
            'description'      => ['nullable', 'string'],
            'logo'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'cover'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'theme'            => ['nullable', Rule::in(ShopTheme::values())],
            'primary_color'    => ['nullable', 'string', 'max:20'],
            'secondary_color'  => ['nullable', 'string', 'max:20'],
            'font_family'      => ['nullable', Rule::in(ShopFontFamily::values())],
            'phone'            => ['nullable', 'string', 'max:30'],
            'email'            => ['nullable', 'email', 'max:150'],
            'website'          => ['nullable', 'string', 'max:255'],
            'social_links'     => ['nullable'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'is_active'        => ['nullable', 'boolean'],
            'is_featured'      => ['nullable', 'boolean'],
            'published_at'     => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'اسم المتجر مطلوب',
            'slug.required'  => 'الرابط المختصر مطلوب',
            'slug.unique'    => 'الرابط المختصر موجود من قبل',
            'user_id.unique' => 'هذا المستخدم لديه متجر بالفعل',
            'logo.image'     => 'الشعار يجب أن يكون صورة',
            'cover.image'    => 'صورة الغلاف يجب أن تكون صورة',
        ];
    }
}
