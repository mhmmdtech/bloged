<?php

namespace App\Services\Report;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Collection;

class ReportProcessor
{
    public static function createReportProcessor($type)
    {
        $reportProcessors = [
            'csv' => CsvReport::class,
            'excel' => ExcelReport::class,
            'pdf' => PdfReport::class,
            'print' => PrintReport::class,
        ];

        if (!isset($reportProcessors[$type])) {
            throw new InvalidArgumentException('Invalid data type.');
        }

        $reportProcessorClass = $reportProcessors[$type];
        return new $reportProcessorClass();
    }
}