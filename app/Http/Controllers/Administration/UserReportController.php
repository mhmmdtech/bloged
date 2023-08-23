<?php

namespace App\Http\Controllers\Administration;

use App\Enums\GenderStatus;
use App\Http\Controllers\Controller;
use App\Exports\UsersExport;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Services\Report\ReportProcessor;
use App\Repositories\UserReportRepository;
use Inertia\Inertia;

class UserReportController extends Controller
{
    public function __construct(
        private UserReportRepository $userReportRepository,
    ) {
    }

    /**
     * Display a report based on province, city and gender parameters.
     */
    public function report()
    {
        $this->authorize('browse analytic', User::class);
        $allowedColumns = ['province', 'city', 'gender'];
        $userInputs = removeNullFromArray(request()->input());
        $reportParameters = array_intersect_key($userInputs, array_flip($allowedColumns));
        $genders = GenderStatus::array();
        if (count($reportParameters) === 0) {
            return Inertia::render('Admin/Users/Report', compact('genders'));
        }

        $results = $this->userReportRepository->generateForWeb(
            $reportParameters,
            $this->administrationPaginatedItemsCount
        );

        $results = new UserCollection($results);
        return Inertia::render('Admin/Users/Report', compact('results', 'genders'));
    }

    /**
     * handle downloading report file
     */
    public function downloadReport(string $format)
    {
        $this->authorize('browse analytic', User::class);

        $allowedFormats = ['print', 'pdf', 'excel', 'csv'];

        if (!in_array($format, $allowedFormats)) {
            return redirect()->route('administration.users.report', request()->query());
        }

        $allowedColumns = ['province', 'city', 'gender'];
        $userInputs = removeNullFromArray(request()->input());
        $reportParameters = array_intersect_key($userInputs, array_flip($allowedColumns));

        if (count($reportParameters) === 0) {
            return redirect()->route('administration.users.report');
        }

        $results = $this->userReportRepository->generateForDownload($reportParameters);

        $reportProcessor = (new ReportProcessor())->createReportProcessor($format, $results, 'users', 'users', UsersExport::class, UserCollection::class);

        $reportFile = $reportProcessor->generate($results, 'users', 'users', UsersExport::class, UserCollection::class);

        return $reportFile;
    }
}