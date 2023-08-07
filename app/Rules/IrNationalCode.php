<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IrNationalCode implements ValidationRule
{
    private function isValidIranianNationalCode($value)
    {
        if (!preg_match('/^\d{10}$/', $value)) {
            return false;
        }

        for ($i = 0; $i < 10; $i++) {
            if (preg_match('/^' . $i . '{10}$/', $value)) {
                return false;
            }
        }

        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $value[$i] * (10 - $i);
        }

        $divideRemaining = $sum % 11;

        $lastDigit = $divideRemaining < 2 ? $divideRemaining : 11 - ($divideRemaining);

        if ((int) $value[9] == $lastDigit) {
            return true;
        }

        return false;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->isValidIranianNationalCode($value)) {
            $fail('The :attribute format is invalid.');
        }
    }
}
