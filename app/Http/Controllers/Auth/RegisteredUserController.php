<?php

namespace App\Http\Controllers\Auth;

use App\Enums\GenderStatus;
use App\Enums\MilitaryStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterationRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\Image\ImageService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        $genders = GenderStatus::array();
        $militaryStatuses = MilitaryStatus::array();
        return Inertia::render('Auth/Register', compact('genders', 'militaryStatuses'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterationRequest $request, ImageService $imageService): RedirectResponse
    {
        $inputs = $request->validated();

        if ($inputs['gender'] != GenderStatus::Male->value)
            $inputs['military_status'] = null;

        $user = User::create($inputs);

        if (isset($inputs['avatar'])) {
            $imageService->setExclusiveDirectory('images');
            $imageService->setImageDirectory('users' . DIRECTORY_SEPARATOR . 'avatars');
            $imageService->setImageName($user->username);
            $user->avatar = $imageService->fitAndSave($inputs['avatar'], 400, 400);
            $user->save();
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}