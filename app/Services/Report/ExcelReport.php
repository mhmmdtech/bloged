<?php

namespace App\Services\Report;

use Illuminate\Database\Eloquent\Collection;

class ExcelReport
{
    /**
     *  genrate excel report file
     * https://docs.laravel-excel.com/
     */
    public function generate(Collection $results, ?string $reportName = null, ?string $reportTemplateName = null, ?string $export = null, ?string $collection = null)
    {
        return (new $export($results))->download($reportName . '.xlsx');
    }
}