<?php

namespace App\Services\Captcha;

class Captcha
{
    public function __construct(private CaptchaRepository $captchaRepository)
    {
        //
    }

    public function getCode()
    {
        return $this->captchaRepository->getCode();
    }

    public function generate()
    {
        $this->captchaRepository->forgetCode();
        $code = generateRandomCode();
        $this->captchaRepository->storeCode($code);
        $faCode = replaceEnDigitsWithFaDigits($code);
        return $this->captchaRepository->generateImage($faCode);
    }

    public function generateForTest()
    {
        $this->captchaRepository->forgetCode();
        $code = generateRandomCode();
        $this->captchaRepository->storeCode($code);
        return $code;
    }
}