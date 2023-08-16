<?php

namespace App\Http\Controllers\Auth;

use App\Enums\GenderStatus;
use App\Enums\MilitaryStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterationRequest;
use App\Models\Province;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\Image\ImageService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        $genders = GenderStatus::array();
        $militaryStatuses = MilitaryStatus::array();
        $provinces = Province::with('cities')->get(['id', 'local_name']);
        return Inertia::render('Auth/Register', compact('genders', 'militaryStatuses', 'provinces'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterationRequest $request, ImageService $imageService): RedirectResponse
    {
        $inputs = $request->validated();

        DB::beginTransaction();
        try {
            $user = User::create($inputs);
            $user->verificationCodes()->create(['token' => generateRandomCode(5, 8)]);
            if (isset($inputs['avatar'])) {
                $imageService->setExclusiveDirectory('images');
                $imageService->setImageDirectory('users' . DIRECTORY_SEPARATOR . 'avatars');
                $imageService->setImageName($user->username);
                $user->avatar = $imageService->fitAndSave($inputs['avatar'], 400, 400);
                $user->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}