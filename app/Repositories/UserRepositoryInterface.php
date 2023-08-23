<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getPaginatedUsers(int $perPage = 5, string $orderedColumn = "id");

    public function getById(int $userId);

    public function create(array $data);

    public function update(User $user, array $data);

    public function delete(User $user);
}