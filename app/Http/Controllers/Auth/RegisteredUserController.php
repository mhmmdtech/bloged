<?php

namespace App\Http\Controllers\Auth;

use App\Enums\GenderStatus;
use App\Enums\MilitaryStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterationRequest;
use App\Models\Province;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Repositories\UserRepositoryInterface;
use App\Services\Image\ImageService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use App\Services\FileManager\FileManager;

class RegisteredUserController extends Controller
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
        //
    }

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
    public function store(RegisterationRequest $request): RedirectResponse
    {
        $inputs = $request->validated();

        $user = $this->userRepository->create($inputs);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}