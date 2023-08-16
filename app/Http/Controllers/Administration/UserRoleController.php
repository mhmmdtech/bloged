<?php

namespace App\Http\Controllers\Administration;

use App\Events\UserModified;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRolesRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
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

    /**
     * Update the specified resource roles.
     */
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
}