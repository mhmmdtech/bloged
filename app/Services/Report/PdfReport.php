<?php

namespace App\Services\Report;

use App\Contracts\ReportContract;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;

class PdfReport implements ReportContract
{
    public function __construct(
        private ?string $reportName = null,
        private ?string $reportTemplateName = null,
        private ?string $collection = null
    ) {
    }

    /**
     *  genrate pdf report file
     */
    public function generate(Collection $results)
    {
        $results = new $this->collection($results);
        $pdf = Pdf::loadView('reports.' . $this->reportTemplateName, compact('results'));
        return $pdf->download($this->reportName . '.pdf');
    }
}