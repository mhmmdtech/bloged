<?php

namespace App\Services\Report;

use App\Contracts\ReportContract;
use Illuminate\Database\Eloquent\Collection;

class ExcelReport implements ReportContract
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
     *  genrate excel report file
     * https://docs.laravel-excel.com/
     */
    public function generate()
    {
        return (new $this->export($this->results))->download($this->reportName . '.xlsx');
    }
}