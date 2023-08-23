<?php

namespace App\Repositories;

interface LogRepositoryInterface
{
    public function getPaginatedLogs(int $perPage = 5, string $orderedColumn = "id");

    public function getById(int $logId);

    public function create(array $data);
}