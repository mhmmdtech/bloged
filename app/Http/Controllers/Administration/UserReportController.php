<?php

namespace App\Http\Controllers\Administration;

use App\Enums\GenderStatus;
use App\Http\Controllers\Controller;
use App\Exports\UsersExport;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Services\Report\ReportProcessor;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class UserReportController extends Controller
{
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

        $query = $this->generateReportQuery($reportParameters);

        $results = $query->paginate($this->administrationPaginatedItemsCount)->withQueryString();
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

        $query = $this->generateReportQuery($reportParameters);
        $results = $query->get();

        $reportProcessor = ReportProcessor::createReportProcessor($format);

        $reportFile = $reportProcessor->generate($results, 'users', 'users', UsersExport::class, UserCollection::class);

        return $reportFile;
    }

    /**
     *  generate sql query for requested report
     */
    private function generateReportQuery($reportParameters): Builder
    {
        $query = User::query();

        foreach ($reportParameters as $parameter => $value) {
            if ($parameter === 'gender') {
                $query->where('gender', $reportParameters['gender']);
                break;
            }
            $query->whereHas($parameter, function ($query) use ($value) {
                $query->whereRaw("MATCH(local_name, latin_name) AGAINST(? IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION)", [$value]);
            });
        }

        return $query;
    }
}