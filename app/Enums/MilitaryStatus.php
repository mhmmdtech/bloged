<?php

namespace App\Enums;

use App\Traits\HasEnum;

enum MilitaryStatus: int
{
    use HasEnum;

    case TemporaryExemption = 1;
    case PermanentExemption = 2;
    case Done = 3;

    public function label(): string
    {
        return static::getLabel($this);
    }

    public static function getLabel(self $value): string
    {
        return match ($value) {
            self::TemporaryExemption => 'temporary exemption',
            self::PermanentExemption => 'permanent exemption',
            self::Done => 'done',
        };
    }
}