<?php

namespace App\Rules;

use App\Services\Captcha\Captcha;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CaptchaValidator implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $code = (Captcha::getCode());

        if ((int) $code !== (int) $value)
            $fail('The :attribute is invalid.');
    }
}