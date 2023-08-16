<?php

namespace App\Http\Controllers;

use App\Enums\GenderStatus;
use App\Enums\MilitaryStatus;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Province;
use App\Services\Image\ImageService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\FileManager\FileManager;

class ProfileController extends Controller
{
    public function __construct(private FileManager $fileManagerService)
    {
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
            'genders' => GenderStatus::array(),
            'militaryStatuses' => MilitaryStatus::array(),
            'provinces' => Province::with('cities')->get(['id', 'local_name']),
            'user' => new UserResource($request->user()->load('province', 'city')),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, ImageService $imageService): RedirectResponse
    {
        $inputs = removeNullFromArray($request->validated());

        if (isset($inputs['avatar'])) {
            $imageService->deleteImage($request->user()->avatar);
            $inputs['avatar'] = $this->fileManagerService
                ->uploadWithResizingImage(
                    $inputs['avatar'],
                    'users' . DIRECTORY_SEPARATOR . 'avatars',
                    $inputs['username'],
                    400,
                    400
                );
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