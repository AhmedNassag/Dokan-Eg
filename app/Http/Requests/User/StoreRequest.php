<?php

namespace App\Http\Requests\User;

use App\Enums\Status;
use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8',
            'user_type' => ['required', new Enum(UserType::class)],
            'status'    => ['required', new Enum(Status::class)],
            'role'      => 'required|exists:roles,id',
        ];
    }
}
