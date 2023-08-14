<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $usersByProvince = DB::table('provinces')
            ->leftJoin('users', 'provinces.id', '=', 'users.province_id')
            ->select('provinces.local_name AS province', DB::raw('COUNT(users.id) AS users'))
            ->groupBy('provinces.local_name')
            ->orderBy('users', 'desc')
            ->take(5)
            ->get();

        return Inertia::render('Admin/Dashboard', compact('usersByProvince'));
    }
}