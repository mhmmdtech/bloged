<?php

namespace App\Http\Controllers\Administration;

use App\Enums\GenderStatus;
use App\Enums\MilitaryStatus;
use App\Events\UserModified;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Province;
use App\Models\User;
use App\Repositories\ProvinceRepository;
use App\Repositories\UserRepository;
use Inertia\Inertia;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{

    public function __construct(
        private UserRepository $userRepository,
        private ProvinceRepository $provinceRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('browse user', User::class);

        $users = new UserCollection(
            $this->userRepository->getPaginatedUsers(
                $this->administrationPaginatedItemsCount,
                $this->normalOrderedColumn
            )
        );

        return Inertia::render('Admin/Users/Index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('add user', User::class);

        $genders = GenderStatus::array();
        $militaryStatuses = MilitaryStatus::array();
        $provinces = $this->provinceRepository->getAllProvincesWithCities($this->normalOrderedColumn);

        return Inertia::render('Admin/Users/Create', compact('genders', 'militaryStatuses', 'provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('add user', User::class);

        $inputs = $request->validated();

        $user = $this->userRepository->create($inputs);

        event(new Registered($user));

        event(new UserModified(auth()->id(), 'create', User::class, $user->id, [], $user->toArray()));

        return redirect()->route('administration.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('read user', $user);

        $user->load('creator', 'province', 'city');

        $user = new UserResource($user);

        return Inertia::render('Admin/Users/Show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('edit user', $user);

        $user->load('province', 'city');

        $genders = GenderStatus::array();
        $militaryStatuses = MilitaryStatus::array();
        $provinces = $this->provinceRepository->getAllProvincesWithCities($this->normalOrderedColumn);

        $user = new UserResource($user);

        return Inertia::render('Admin/Users/Edit', compact('user', 'genders', 'militaryStatuses', 'provinces'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('edit user', $user);

        $inputs = removeNullFromArray($request->validated());

        $result = $this->userRepository->update($user, $inputs);

        event(new UserModified(auth()->id(), 'update', User::class, $user->id, $result['old_user'], $result['new_user']));

        return redirect()->route('administration.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete user', $user);

        $this->userRepository->delete($user);

        event(new UserModified(auth()->id(), 'destroy', User::class, $user->id, $user->toArray(), []));

        return redirect()->route('administration.users.index');
    }
}