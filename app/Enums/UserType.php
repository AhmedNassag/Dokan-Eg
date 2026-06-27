<?php

namespace App\Enums;

enum UserType: string
{
    case ADMIN = 'admin';
    case MERCHANT = 'merchant';
    case MARKETER = 'marketer';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}