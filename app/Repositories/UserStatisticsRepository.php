<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class UserStatisticsRepository implements UserStatisticsRepositoryInterface
{
    public function getProvincesWithMostUsers(int $limit = 5)
    {
        return DB::table('provinces')
            ->leftJoin('users', 'provinces.id', '=', 'users.province_id')
            ->select('provinces.local_name AS province', DB::raw('COUNT(users.id) AS users'))
            ->groupBy('provinces.local_name')
            ->orderBy('users', 'desc')
            ->take($limit)
            ->get();
    }
}