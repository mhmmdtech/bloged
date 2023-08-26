<?php

namespace App\Http\Controllers;

use App\Enums;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Province;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepositoryInterface
    ) {
        //
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'genders' => Enums\GenderStatus::array(),
            'militaryStatuses' => Enums\MilitaryStatus::array(),
            'provinces' => Province::with('cities')->get(['id', 'local_name']),
            'user' => new UserResource($request->user()->load('province', 'city')),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $inputs = removeNullFromArray($request->validated());

        $this->userRepositoryInterface->update($request->user(), $inputs);

        return redirect()->route('profile.edit');
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

        $this->userRepositoryInterface->deleteSelfAccount($user);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}