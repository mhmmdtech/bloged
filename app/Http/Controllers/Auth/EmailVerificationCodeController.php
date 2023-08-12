<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailVerificationCodeRequest;
use App\Models\UserVerificationCode;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Auth\Events\Verified;

class EmailVerificationCodeController extends Controller
{
    /**
     * Show the form for verifying the resource.
     */
    public function show()
    {
        if (request()->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        }

        return Inertia::render('Auth/VerifyCode');
    }

    /**
     * Update the specified resource in storage.
     */
    public function verify(EmailVerificationCodeRequest $request)
    {
        $user = request()->user()->load('verificationCodes');
        $inputs = $request->validated();

        if ($user->verificationCodes->last()->token === $inputs['token']) {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }

            return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        }
    }
}