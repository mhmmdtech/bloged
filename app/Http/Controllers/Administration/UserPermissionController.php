<?php

namespace App\Http\Controllers\Administration;

use App\Events\UserModified;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Admin\UpdateUserPermissionsRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\PermissionRepository;
use App\Repositories\UserRepository;
use Inertia\Inertia;

class UserPermissionController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private PermissionRepository $permissionRepository,
    ) {
    }

    /**
     * Display a listing of all permissions.
     */
    public function permissions(User $user)
    {
        $this->authorize('edit user', $user);

        $permissions = $this->permissionRepository->getAll();
        $currentPermissions = $this->userRepository->getUserDirectPermissions($user);
        $user = new UserResource($user);

        return Inertia::render('Admin/Users/Permissions', compact('user', 'permissions', 'currentPermissions'));
    }

    /**
     * Update the specified resource permissions.
     */
    public function updatePermissions(UpdateUserPermissionsRequest $request, User $user)
    {
        $this->authorize('edit user', $user);

        $inputs = $request->validated();

        $results = $this->userRepository->updatePermissions($user, $inputs);

        event(new UserModified(auth()->id(), 'update permissions', User::class, $user->id, $results['old_permissions'], $results['new_permissions']));

        return redirect()->route('administration.users.show', $user->id);
    }
}