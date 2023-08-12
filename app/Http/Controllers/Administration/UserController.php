<?php

namespace App\Http\Controllers\Administration;

use App\Enums\GenderStatus;
use App\Enums\MilitaryStatus;
use App\Events\UserModified;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdvancedSearchUserRequest;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserPasswordRequest;
use App\Http\Requests\Admin\UpdateUserPermissionsRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\UpdateUserRolesRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Image\ImageService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('browse user', User::class);

        $users = new UserCollection(User::with('roles')->latest()->paginate(5));

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

        return Inertia::render('Admin/Users/Create', compact('genders', 'militaryStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, ImageService $imageService)
    {
        $this->authorize('add user', User::class);

        $inputs = $request->validated();

        if ($inputs['gender'] != GenderStatus::Male->value)
            $inputs['military_status'] = null;

        $user = auth()->user()->users()->create($inputs);

        $user->verificationCodes()->create(['token' => generateRandomCode(5, 8)]);

        if (isset($inputs['avatar'])) {
            $imageService->setExclusiveDirectory('images');
            $imageService->setImageDirectory('users' . DIRECTORY_SEPARATOR . 'avatars');
            $imageService->setImageName($user->username);
            $user->avatar = $imageService->fitAndSave($inputs['avatar'], 400, 400);
            $user->save();
        }

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

        $user->load('creator');

        $user = new UserResource($user);

        return Inertia::render('Admin/Users/Show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('edit user', $user);

        $genders = GenderStatus::array();
        $militaryStatuses = MilitaryStatus::array();

        $user = new UserResource($user);

        return Inertia::render('Admin/Users/Edit', compact('user', 'genders', 'militaryStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, ImageService $imageService, User $user)
    {
        $this->authorize('edit user', $user);

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

        $oldUser = clone $user;
        $user->fill($inputs);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->verificationCodes()->create(['token' => generateRandomCode(5, 8)]);
        }

        $user->save();

        event(new UserModified(auth()->id(), 'update', User::class, $user->id, $oldUser->toArray(), $user->toArray()));

        return redirect()->route('administration.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete user', $user);

        $user->delete();

        event(new UserModified(auth()->id(), 'destroy', User::class, $user->id, $user->toArray(), []));

        return redirect()->route('administration.users.index');
    }

    /**
     * Display a listing of all roles.
     */
    public function roles(User $user)
    {
        $this->authorize('edit user', $user);

        $roles = Role::all();
        $user = new UserResource($user);
        $currentRoles = $user->getRoleNames()->toArray();

        return Inertia::render('Admin/Users/Roles', compact('user', 'roles', 'currentRoles'));
    }

    public function updateRoles(UpdateUserRolesRequest $request, User $user)
    {
        $this->authorize('edit user', $user);

        $inputs = $request->validated();

        $oldRoles = $user->getRoleNames();

        $user->syncRoles($inputs['currentRoles']);

        $newRoles = $user->getRoleNames();

        event(new UserModified(auth()->id(), 'update roles', User::class, $user->id, $oldRoles->toArray(), $newRoles->toArray()));

        return redirect()->route('administration.users.show', $user->id);
    }

    /**
     * Display a listing of all permissions.
     */
    public function permissions(User $user)
    {
        $this->authorize('edit user', $user);

        $permissions = Permission::all();
        $user = new UserResource($user);
        $currentPermissions = $user->getDirectPermissions()->pluck('name')->toArray();

        return Inertia::render('Admin/Users/Permissions', compact('user', 'permissions', 'currentPermissions'));
    }

    public function updatePermissions(UpdateUserPermissionsRequest $request, User $user)
    {
        $this->authorize('edit user', $user);

        $inputs = $request->validated();

        $oldPermissions = $user->getPermissionNames();

        $user->syncPermissions($inputs['currentPermissions']);

        $newPermissions = $user->getPermissionNames();

        event(new UserModified(auth()->id(), 'update permissions', User::class, $user->id, $oldPermissions->toArray(), $newPermissions->toArray()));

        return redirect()->route('administration.users.show', $user->id);
    }

    /**
     * Show the form for editing the specified user password.
     */
    public function editPassword(User $user)
    {
        $this->authorize('edit user', $user);

        $user = new UserResource($user);

        return Inertia::render('Admin/Users/EditPassword', compact('user'));
    }

    /**
     * Update the specified user password in storage.
     */
    public function updatePassword(UpdateUserPasswordRequest $request, User $user)
    {
        $this->authorize('edit user', $user);

        $inputs = $request->validated();

        $user->update(['password' => $inputs['password']]);

        event(new UserModified(auth()->id(), 'update password', User::class, $user->id, $user->toArray(), $user->toArray()));

        return redirect()->route('administration.users.index');
    }

    /**
     * Display a listing of the searched resource.
     */
    public function advancedSearch()
    {
        $this->authorize('browse user', User::class);

        $results = [];
        $allowedColumns = ['first_name', 'last_name', 'national_code', 'mobile_number', 'email', 'username', 'creator_id', 'birthday', 'gender', 'military_status'];
        $userInputs = removeNullFromArray(request()->input());
        $allowedInputs = array_intersect_key($userInputs, array_flip($allowedColumns));

        $creators = User::whereHas('roles.permissions', function (Builder $query) {
            $query->where('name', 'add user');
        })->get(['id', 'username']);

        $genders = GenderStatus::array();

        $militaryStatuses = MilitaryStatus::array();

        if (count($allowedInputs) > 0) {
            $results = new UserCollection(User::with('roles')->where($allowedInputs)->latest()->paginate(5));
            return Inertia::render('Admin/Users/AdvancedSearch', compact('results', 'creators', 'genders', 'militaryStatuses'));
        }
        $results = new UserCollection(User::with('roles')->latest()->paginate(5));
        return Inertia::render('Admin/Users/AdvancedSearch', compact('results', 'creators', 'genders', 'militaryStatuses'));
    }
}