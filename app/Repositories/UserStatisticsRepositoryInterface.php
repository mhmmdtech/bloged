<?php

namespace App\Repositories;

interface UserStatisticsRepositoryInterface
{
    public function getProvincesWithMostUsers(int $limit = 5);
}