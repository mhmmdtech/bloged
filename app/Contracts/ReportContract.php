<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface YourContractName
{
    public function generate(Collection $results, ?string $reportName, ?string $reportTemplateName, ?string $export, ?string $collection);
}