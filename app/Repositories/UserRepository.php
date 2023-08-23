<?php

namespace App\Repositories;

use App\Models\User;
use App\Services\FileManager\FileManager;
use Illuminate\Support\Facades\DB;

class UserRepository
{

    public function __construct(
        private FileManager $fileManagerService
    ) {
    }

    public function getPaginatedUsers(int $perPage = 5, string $orderedColumn = "id")
    {
        return User::with('roles')->latest($orderedColumn)->paginate($perPage);
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

            $user->verificationCodes()->create(['token' => generateRandomCode(5, 8)]);

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
            $user->verificationCodes()->create(['token' => generateRandomCode(5, 8)]);
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
}