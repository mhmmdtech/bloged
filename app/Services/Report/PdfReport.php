<?php

namespace App\Services\Report;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;

class pdfReport
{
    /**
     *  genrate pdf report file
     */
    public function generate(Collection $results, ?string $reportName = null, ?string $reportTemplateName = null, ?string $export = null, ?string $collection = null)
    {
        $results = new $collection($results);
        $pdf = Pdf::loadView('reports.' . $reportTemplateName, compact('results'));
        return $pdf->download($reportName . '.pdf');
    }
}