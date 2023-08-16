<?php

namespace App\Http\Controllers\Administration;

use App\Enums\GenderStatus;
use App\Http\Controllers\Controller;
use App\Exports\UsersExport;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
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

        $reportMethods = [
            'print' => 'printReport',
            'pdf' => 'pdfReport',
            'excel' => 'excelReport',
            'csv' => 'csvReport',
        ];

        $result = NULL;

        if (isset($reportMethods[$format])) {
            $methodName = $reportMethods[$format];
            $result = $this->$methodName($reportParameters);
        }

        return $result;
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

    /**
     *  generate printable report file
     */
    private function printReport($reportParameters)
    {
        $query = $this->generateReportQuery($reportParameters);

        $users = $query->get();
        $users = new UserCollection($users);
        return Inertia::render('Admin/Users/PrintableReport', compact('users'));
    }

    /**
     *  genrate pdf report file
     */
    private function pdfReport($reportParameters)
    {
        $query = $this->generateReportQuery($reportParameters);

        $users = $query->get();
        $users = new UserCollection($users);
        $pdf = Pdf::loadView('reports.users', compact('users'));
        return $pdf->download('users-report.pdf');
    }

    /**
     *  genrate excel report file
     * https://docs.laravel-excel.com/
     */
    private function excelReport($reportParameters)
    {
        $query = $this->generateReportQuery($reportParameters);

        $result = $query->get();

        return (new UsersExport($result))->download('users.xlsx');
    }

    /**
     *  genrate csv report file
     * https://github.com/vitorccs/laravel-csv
     */
    private function csvReport($reportParameters)
    {
        $query = $this->generateReportQuery($reportParameters);

        $result = $query->get();

        return (new UsersExport($result))->download('users.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);

    }
}