<?php

namespace App\Services\Report;

use App\Contracts\ReportMethodFactoryContract;

class ExcelReportFactory implements ReportMethodFactoryContract
{
    public function __construct(
        private ?string $reportName = null,
        private ?string $export = null,
    ) {
    }

    public function create(): ExcelReport
    {
        return new ExcelReport($this->reportName, $this->export);
    }
}