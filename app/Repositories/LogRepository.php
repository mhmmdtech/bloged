<?php

namespace App\Repositories;

use App\Models\Log;

class LogRepository implements LogRepositoryInterface
{
    public function getPaginatedLogs(int $perPage = 5, string $orderedColumn = "id")
    {
        return Log::with('actioner')->latest($orderedColumn)->paginate($perPage);
    }

    public function getById(int $logId)
    {
        return Log::with('actioner')
            ->findOrFail($logId);
    }

    public function create(array $data)
    {
        return Log::create($data);
    }
}