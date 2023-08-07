<?php

namespace App\Enums;

use App\Traits\HasEnum;

enum GenderStatus: int
{
    use HasEnum;

    case Male = 1;
    case Female = 2;

    public function label(): string
    {
        return static::getLabel($this);
    }

    public static function getLabel(self $value): string
    {
        return match ($value) {
            self::Male => 'male',
            self::Female => 'female',
        };
    }
}
