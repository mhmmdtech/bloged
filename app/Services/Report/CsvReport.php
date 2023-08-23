<?php

namespace App\Services\Report;

use App\Exports\UsersExport;
use Illuminate\Database\Eloquent\Collection;

class CsvReport
{
    /**
     *  genrate csv report file
     * https://github.com/vitorccs/laravel-csv
     */
    public function generate(Collection $results, ?string $reportName = null, ?string $reportTemplateName = null,?string $export = null,?string $collection = null)
    {
        return (new $export($results))->download($reportName . '.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);

    }
}