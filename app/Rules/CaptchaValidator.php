<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Services\Captcha\Captcha;
use App\Services\Captcha\CaptchaRepository;

class CaptchaValidator implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Initialize captcha service
        $captchaRepository = new CaptchaRepository();
        $captcha = new Captcha($captchaRepository);
        $code = $captcha->getCode();

        if ((int) $code !== (int) $value)
            $fail('The :attribute is invalid.');
    }
}