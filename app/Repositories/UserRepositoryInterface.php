<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getPaginatedUsers(int $perPage = 5, string $orderedColumn = "id");

    public function getUserDirectPermissions(User $user);

    public function getAllUsersWithSpecificPermission(string $permission);

    public function getUsersBySearchParams(array $allowedInputs, int $perPage = 5, string $orderedColumn = "id");

    public function getUserRolesName(User $user);

    public function getById(int $userId);

    public function create(array $data);

    public function update(User $user, array $data);

    public function delete(User $user);

    public function deleteSelfAccount(User $user);

    public function updatePassword(User $user, array $data);

    public function updatePermissions(User $user, array $data);

    public function updateRoles(User $user, array $data);
}