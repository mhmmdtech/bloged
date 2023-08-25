<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Repositories\UserStatisticsRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function __construct(
        private UserStatisticsRepositoryInterface $userStatisticsRepository
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $usersByProvince = $this->userStatisticsRepository->getProvincesWithMostUsers(5);

        return Inertia::render('Admin/Dashboard', compact('usersByProvince'));
    }
}