<?php

namespace App\Http\Controllers;

use App\Services\Captcha\Captcha;
use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Captcha $captcha)
    {
        return $captcha->generate();
    }
}