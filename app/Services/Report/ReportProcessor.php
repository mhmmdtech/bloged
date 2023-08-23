<?php

namespace App\Services\Report;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Collection;

class ReportProcessor
{
    public function createReportProcessor(
        $type,
        $results,
        $reportName,
        $reportTemplateName,
        $export,
        $collection
    ) {
        $reportProcessors = [
            'csv' => [
                'factory' => CsvReportFactory::class,
                'parameters' => [
                    'reportName' => $reportName,
                    'export' => $export,
                ]
            ],
            'excel' => [
                'factory' => ExcelReportFactory::class,
                'parameters' => [
                    'reportName' => $reportName,
                    'export' => $export,
                ]
            ],
            'pdf' => [
                'factory' => PdfReportFactory::class,
                'parameters' => [
                    'reportName' => $reportName,
                    'reportTemplateName' => $reportTemplateName,
                    'collection' => $collection,
                ]
            ],
            'print' => [
                'factory' => PrintReportFactory::class,
                'parameters' => [
                    'reportTemplateName' => $reportTemplateName,
                    'collection' => $collection,
                ]
            ],
        ];

        if (!isset($reportProcessors[$type])) {
            throw new InvalidArgumentException('Invalid data type.');
        }

        $reportFactoryClass = $reportProcessors[$type]['factory'];
        $reportFactoryParameters = $reportProcessors[$type]['parameters'];
        $reportFactoryObject = new $reportFactoryClass(...$reportFactoryParameters);
        $reportProcessor = $reportFactoryObject->create();
        return $reportProcessor->generate($results);
    }
}