<?php

namespace App\Http\Controllers;

use App\Enums\GenderStatus;
use App\Enums\MilitaryStatus;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\Image\ImageService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'genders' => GenderStatus::array(),
            'militaryStatuses' => MilitaryStatus::array(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, ImageService $imageService): RedirectResponse
    {
        $inputs = removeNullFromArray($request->validated());

        if ($inputs['gender'] != GenderStatus::Male->value)
            $inputs['military_status'] = null;

        if (isset($inputs['avatar'])) {
            $imageService->deleteImage($request->user()->avatar);
            $imageService->setExclusiveDirectory('images');
            $imageService->setImageDirectory('users' . DIRECTORY_SEPARATOR . 'avatars');
            $imageService->setImageName($inputs['username']);
            $inputs['avatar'] = $imageService->fitAndSave($inputs['avatar'], 400, 400);
        }

        $request->user()->fill($inputs);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
            $request->user()->verificationCodes()->create(['token' => generateRandomCode(5, 8)]);
        }

        $request->user()->save();

        return redirect()->route('administration.users.index');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}