<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ReportMethodFactoryContract
{
    public function create(): ReportContract;
}