<?php

namespace App\Services\Report;

use App\Contracts\ReportContract;
use Illuminate\Database\Eloquent\Collection;

class ExcelReport implements ReportContract
{
    public function __construct(
        private ?string $reportName = null,
        private ?string $export = null,
    ) {
    }

    /**
     *  genrate excel report file
     * https://docs.laravel-excel.com/
     */
    public function generate(Collection $results)
    {
        return (new $this->export($results))->download($this->reportName . '.xlsx');
    }
}