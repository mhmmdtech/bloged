<?php

namespace App\Services\Report;

use Inertia\Inertia;
use Illuminate\Database\Eloquent\Collection;

class PrintReport
{
    /**
     *  generate printable report file
     */
    public function generate(Collection $results, ?string $reportName = null, ?string $reportTemplateName = null, ?string $export = null, ?string $collection = null)
    {
        $results = new $collection($results);
        return Inertia::render('Admin/Reports/' . ucfirst($reportTemplateName), compact('results'));
    }
}