<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Database\Eloquent\Builder;

class UsersExport implements FromQuery
{
    use Exportable;

    public function __construct(public Builder $query)
    {
        //
    }

    public function query()
    {
        return $this->query;
    }
}