<?php

namespace App\Services\Report;

use App\Contracts\ReportContract;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Collection;

class PrintReport implements ReportContract
{
    public function __construct(
        private ?string $reportTemplateName = null,
        private ?string $collection = null
    ) {
    }

    /**
     *  generate printable report file
     */
    public function generate(Collection $results)
    {
        $results = new $this->collection($results);

        return Inertia::render('Admin/Reports/' . ucfirst($this->reportTemplateName), compact('results'));
    }
}