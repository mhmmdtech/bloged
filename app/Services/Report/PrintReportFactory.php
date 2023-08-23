<?php

namespace App\Services\Report;

use App\Contracts\ReportMethodFactoryContract;

class PrintReportFactory implements ReportMethodFactoryContract
{
    public function __construct(
        private ?string $reportTemplateName = null,
        private ?string $collection = null
    ) {
    }

    public function create(): PrintReport
    {
        return new PrintReport($this->reportTemplateName, $this->collection);
    }
}