<?php

namespace App\Services\Report;

use App\Contracts\ReportMethodFactoryContract;

class CsvReportFactory implements ReportMethodFactoryContract
{
    public function __construct(
        private ?string $reportName = null,
        private ?string $export = null,
    ) {
    }

    public function create(): CsvReport
    {
        return new CsvReport($this->reportName, $this->export);
    }
}