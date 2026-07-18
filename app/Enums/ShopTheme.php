<?php

namespace App\Enums;

enum ShopTheme: string
{
    case DEFAULT = 'default';
    case MODERN = 'modern';
    case MINIMAL = 'minimal';
    case DARK = 'dark';
    case LIGHT = 'light';
    case ELEGANT = 'elegant';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::DEFAULT => 'Default',
            self::MODERN  => 'Modern',
            self::MINIMAL => 'Minimal',
            self::DARK    => 'Dark',
            self::LIGHT   => 'Light',
            self::ELEGANT => 'Elegant',
        };
    }

    public static function options(): array
    {
        return array_map(fn (self $case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }
}
