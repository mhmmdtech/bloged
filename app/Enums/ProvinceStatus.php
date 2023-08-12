<?php

namespace App\Enums;

use App\Traits\HasEnum;

enum ProvinceStatus: int
{
    use HasEnum;

    case Active = 1;
    case Disable = 2;

    public function label(): string
    {
        return static::getLabel($this);
    }

    public static function getLabel(self $value): string
    {
        return match ($value) {
            self::Active => 'active',
            self::Disable => 'disable',
        };
    }
}