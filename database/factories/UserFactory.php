<?php

namespace Database\Factories;

use App\Enums\UserType;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'user_type' => UserType::MARKETER,
            'status' => Status::PENDING,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => UserType::ADMIN,
            'status' => Status::APPROVED,
        ]);
    }

    public function merchant(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => UserType::MERCHANT,
            'status' => Status::APPROVED,
        ]);
    }

    public function shippingRepresentative(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => UserType::SHIPPING_REPRESENTATIVE,
            'status' => Status::APPROVED,
        ]);
    }
}
