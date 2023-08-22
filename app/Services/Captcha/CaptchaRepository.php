<?php

namespace App\Services\Captcha;

use Illuminate\Support\Facades\Session;

class CaptchaRepository
{
    public function forgetCode()
    {
        session()->forget('captcha_code');
    }

    public function storeCode($captchaCode)
    {
        session()->put('captcha_code', $captchaCode);
    }

    public function getCode()
    {
        return session()->get('captcha_code');
    }

    public function generateImage($code, $width = 150, $height = 50)
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
}