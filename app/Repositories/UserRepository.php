<?php

namespace App\Repositories;

use App\Models\User;
use App\Services\FileManager\FileManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class UserRepository implements UserRepositoryInterface
{

    public function __construct(
        private FileManager $fileManagerService
    ) {
    }

    public function getPaginatedUsers(int $perPage = 5, string $orderedColumn = "id")
    {
        return User::with('roles')->latest($orderedColumn)->paginate($perPage);
    }

    public function getUserDirectPermissions(User $user)
    {
        return $user->getDirectPermissions()->pluck('name')->toArray();
    }

    public function getAllUsersWithSpecificPermission(string $permission)
    {
        return User::whereHas('roles.permissions', function (Builder $query) use ($permission) {
            $query->where('name', $permission);
        })->get();
    }

    public function getUsersBySearchParams(array $allowedInputs, int $perPage = 5, string $orderedColumn = "id")
    {
        return User::with('roles')
            ->where($allowedInputs)
            ->latest($orderedColumn)
            ->paginate($perPage);
    }

    public function getUserRolesName(User $user)
    {
        return $user->getRoleNames()->toArray();
    }

    public function getById(int $userId)
    {
        return User::with('creator', 'province', 'city')
            ->findOrFail($userId);
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $user = auth()->user()->users()->create($data);

            $user->verificationCodes()->create([
                'token' => generateRandomCode(5, 8),
                'expires_at' => now()->addHour(),
            ]);

            if (isset($data['avatar'])) {
                $user->avatar = $this->fileManagerService
                    ->uploadWithResizingImage(
                        $data['avatar'],
                        'users' . DIRECTORY_SEPARATOR . 'avatars',
                        $user->username,
                        400,
                        400
                    );
                $user->save();
            }
            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(User $user, array $data)
    {
        if (isset($data['avatar'])) {
            $this->fileManagerService->deleteImage($user->avatar);
            $user->avatar = $this->fileManagerService
                ->uploadWithResizingImage(
                    $data['avatar'],
                    'users' . DIRECTORY_SEPARATOR . 'avatars',
                    $user->username,
                    400,
                    400
                );
            $user->save();
            unset($data['avatar']);
        }

        $oldUser = clone $user;
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->verificationCodes()->create([
                'token' => generateRandomCode(5, 8),
                'expires_at' => now()->addHour(),
            ]);
        }

        $user->save();

        return [
            'new_user' => $user->toArray(),
            'old_user' => $oldUser->toArray(),
        ];
    }

    public function delete(User $user)
    {
        $this->fileManagerService->deleteImage($user->avatar);

        $user->delete();
    }
    public function updatePassword(User $user, array $data)
    {
        $user->update(['password' => $data['password']]);
    }

    public function updatePermissions(User $user, array $data)
    {
        $oldPermissions = $user->getPermissionNames();

        $user->syncPermissions($data['currentPermissions']);

        $newPermissions = $user->getPermissionNames();

        return [
            'old_permissions' => $oldPermissions->toArray(),
            'new_permissions' => $newPermissions->toArray(),
        ];
    }

    public function updateRoles(User $user, array $data)
    {

        $oldRoles = $user->getRoleNames();

        $user->syncRoles($data['currentRoles']);

        $newRoles = $user->getRoleNames();

        return [
            'old_roles' => $oldRoles->toArray(),
            'new_roles' => $newRoles->toArray(),
        ];
    }
}