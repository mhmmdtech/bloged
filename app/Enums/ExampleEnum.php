<?php

namespace App\Enums;

use App\Traits\HasEnum;

enum ExampleEnum: int
{
    use HasEnum;

    case Draft = 1;
    case Published = 2;
    case Archived = 3;

    public function label(): string
    {
        return static::getLabel($this);
    }

    public static function getLabel(self $value): string
    {
        return match ($value) {
            self::Draft => 'draft',
            self::Published => 'published',
            self::Archived => 'archived',
        };
    }
}