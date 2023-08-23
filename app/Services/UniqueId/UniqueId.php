<?php

namespace App\Services\UniqueId;

class UniqueId
{
    public function __invoke($modelId, $outputLength = 11, $maxModelIdLength = 5)
    {
        $int = pow(10, ($outputLength - 1) - $maxModelIdLength);
        $rand = (string) random_int($int, (10 * $int) - 1);

        return str_pad($modelId . $rand, $outputLength, '0', STR_PAD_LEFT);
    }
}