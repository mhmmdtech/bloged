<?php

namespace App\Repositories;

interface LogRepositoryInterface
{
    public function getById(int $logId);
}