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
     * Update the specified resource in storage.
     */
    public function verify(EmailVerificationCodeRequest $request)
    {
        $user = request()->user()->load('verificationCodes');
        $inputs = $request->validated();

        if ($user->verificationCodes->last()->token !== $inputs['token']) {
            return back()->with('status', [
                'name' => 'verification-code-issue',
                'message' => 'Please enter the last received code'
            ]);
        }

        if (now()->greaterThan($user->verificationCodes->last()->expires_at)) {
            return back()->with('status', [
                'name' => 'verification-code-issue',
                'message' => 'The entered code has been expired'
            ]);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
    }
}