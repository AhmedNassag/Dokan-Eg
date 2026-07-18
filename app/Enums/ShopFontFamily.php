<?php

namespace App\Enums;

enum ShopFontFamily: string
{
    case CAIRO = 'Cairo';
    case TAJAWAL = 'Tajawal';
    case ALMARAI = 'Almarai';
    case NOTO_KUFI_ARABIC = 'Noto Kufi Arabic';
    case INTER = 'Inter';
    case ROBOTO = 'Roboto';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return $this->value;
    }

    public static function options(): array
    {
        return array_map(fn (self $case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }
}
