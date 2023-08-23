<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;


interface UserReportRepositoryInterface
{
    public function generateForWeb(array $reportParameters, int $perPage = 5);

    public function generateForDownload(array $reportParameters);

    public function generateQuery(array $reportParameters): Builder;
}