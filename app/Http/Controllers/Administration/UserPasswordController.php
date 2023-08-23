<?php

namespace App\Http\Controllers\Administration;

use App\Events\UserModified;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Inertia\Inertia;

class UserPasswordController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
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

        $this->userRepository->updatePassword($user, $inputs);

        event(new UserModified(auth()->id(), 'update password', User::class, $user->id, $user->toArray(), $user->toArray()));

        return redirect()->route('administration.users.index');
    }
}