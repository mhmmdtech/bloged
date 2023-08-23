<?php

namespace App\Services\Report;

use App\Contracts\ReportContract;
use Illuminate\Database\Eloquent\Collection;

class CsvReport implements ReportContract
{
    public function __construct(
        private ?string $reportName = null,
        private ?string $export = null,
    ) {
    }
    /**
     *  genrate csv report file
     * https://github.com/vitorccs/laravel-csv
     */
    public function generate(Collection $results)
    {
        return (new $this->export($results))->download($this->reportName . '.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);

    }
}