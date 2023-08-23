<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ReportContract
{
    public function generate();
}