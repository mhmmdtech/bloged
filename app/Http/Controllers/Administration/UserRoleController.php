<?php

namespace App\Http\Controllers\Administration;

use App\Events\UserModified;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRolesRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private RoleRepository $roleRepository,
    ) {
    }
    /**
     * Display a listing of all roles.
     */
    public function roles(User $user)
    {
        $this->authorize('edit user', $user);

        $roles = $this->roleRepository->getAll();
        $currentRoles = $this->userRepository->getUserRolesName($user);
        $user = new UserResource($user);

        return Inertia::render('Admin/Users/Roles', compact('user', 'roles', 'currentRoles'));
    }

    /**
     * Update the specified resource roles.
     */
    public function updateRoles(UpdateUserRolesRequest $request, User $user)
    {
        $this->authorize('edit user', $user);

        $inputs = $request->validated();

        $results = $this->userRepository->updateRoles($user, $inputs);

        event(new UserModified(auth()->id(), 'update roles', User::class, $user->id, $results['old_roles'], $results['new_roles']));

        return redirect()->route('administration.users.show', $user->id);
    }
}