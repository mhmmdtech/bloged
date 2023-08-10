<?php

namespace App\Services\Captcha;

class Captcha
{
    private function forgetCode()
    {
        session()->forget('captcha_code');
    }

    private function generateRandomCode()
    {
        $minDigits = 5;
        $maxDigits = 8;

        $minValue = pow(10, $minDigits);
        $maxValue = pow(10, $maxDigits) - 1;

        return random_int($minValue, $maxValue);
    }

    private function storeCode($captchaCode)
    {
        session(['captcha_code' => $captchaCode]);
    }

    private function generateImage($code, $width = 150, $height = 50)
    {
        // https://www.aparat.com/v/ojA3L
        $image = imagecreate($width, $height);
        imagecolorallocate($image, 255, 255, 255);
        $color = imagecolorallocate($image, 99, 102, 241);
        $size = 20;
        $font = public_path('fonts/Shabnam.ttf');
        imagefttext($image, $size, 0, 15, 33, $color, $font, $code);
        imagepng($image);
    }

    public static function getCode()
    {
        return session()->get('captcha_code');
    }

    public function generate()
    {
        $this->forgetCode();
        $code = $this->generateRandomCode();
        $this->storeCode($code);
        $faCode = replaceEnDigitsWithFaDigits($code);
        return $this->generateImage($faCode);
    }
}