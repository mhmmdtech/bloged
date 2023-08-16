<?php

namespace App\Http\Controllers\Administration;

use App\Events\UserModified;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Admin\UpdateUserPermissionsRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Inertia\Inertia;

class UserPermissionController extends Controller
{
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

    /**
     * Update the specified resource permissions.
     */
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
}