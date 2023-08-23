<?php

namespace App\Services\Report;

use App\Contracts\ReportContract;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;

class pdfReport implements ReportContract
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
     *  genrate pdf report file
     */
    public function generate()
    {
        $results = new $this->collection($this->results);
        $pdf = Pdf::loadView('reports.' . $this->reportTemplateName, compact('results'));
        return $pdf->download($this->reportName . '.pdf');
    }
}