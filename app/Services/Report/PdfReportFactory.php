<?php

namespace App\Services\Report;

use App\Contracts\ReportMethodFactoryContract;

class PdfReportFactory implements ReportMethodFactoryContract
{
    public function __construct(
        private ?string $reportName = null,
        private ?string $reportTemplateName = null,
        private ?string $collection = null
    ) {
    }

    public function create(): PdfReport
    {
        return new PdfReport($this->reportName, $this->reportTemplateName, $this->collection);
    }
}