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
use Inertia\Inertia;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use App\Services\FileManager\FileManager;

class UserController extends Controller
{
    public function __construct(private FileManager $fileManagerService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('browse user', User::class);

        $users = new UserCollection(User::with('roles')->latest($this->normalOrderedColumn)->paginate($this->administrationPaginatedItemsCount));

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
        $provinces = Province::with('cities')->get(['id', 'local_name']);

        return Inertia::render('Admin/Users/Create', compact('genders', 'militaryStatuses', 'provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('add user', User::class);

        $inputs = $request->validated();

        DB::beginTransaction();

        try {
            $user = auth()->user()->users()->create($inputs);

            $user->verificationCodes()->create(['token' => generateRandomCode(5, 8)]);

            if (isset($inputs['avatar'])) {
                $user->avatar = $this->fileManagerService
                    ->uploadWithResizingImage(
                        $inputs['avatar'],
                        'users' . DIRECTORY_SEPARATOR . 'avatars',
                        $user->username,
                        400,
                        400
                    );
                $user->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
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
        $provinces = Province::with('cities')->get(['id', 'local_name']);

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

        if (isset($inputs['avatar'])) {
            $this->fileManagerService->deleteImage($request->user()->avatar);
            $user->avatar = $this->fileManagerService
                ->uploadWithResizingImage(
                    $inputs['avatar'],
                    'users' . DIRECTORY_SEPARATOR . 'avatars',
                    $user->username,
                    400,
                    400
                );
            $user->save();
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

        $this->fileManagerService->deleteImage($user->avatar);

        event(new UserModified(auth()->id(), 'destroy', User::class, $user->id, $user->toArray(), []));

        return redirect()->route('administration.users.index');
    }
}