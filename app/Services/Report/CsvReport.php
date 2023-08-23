<?php

namespace App\Services\Report;

use App\Contracts\ReportContract;
use Illuminate\Database\Eloquent\Collection;

class CsvReport implements ReportContract
{
    public function __construct(
        private Collection $results,
        private ?string $reportName = null,
        private ?string $reportTemplateName = null,
        private ?string $export = null,
        private ?string $collection = null
    ) {
    }
    /**
     *  genrate csv report file
     * https://github.com/vitorccs/laravel-csv
     */
    public function generate()
    {
        return (new $this->export($this->results))->download($this->reportName . '.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);

    }
}