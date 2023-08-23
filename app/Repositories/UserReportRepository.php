<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserReportRepository
{
    public function generateForWeb(array $reportParameters, int $perPage = 5)
    {
        $query = $this->generateReportQuery($reportParameters);
        return $query->paginate($perPage)->withQueryString();
    }

    public function generateForDownload(array $reportParameters)
    {
        $query = $this->generateReportQuery($reportParameters);
        return $query->get();
    }

    public function generateReportQuery(array $reportParameters): Builder
    {
        $query = User::query();

        foreach ($reportParameters as $parameter => $value) {
            if ($parameter === 'gender') {
                $query->where('gender', $reportParameters['gender']);
                break;
            }
            $query->whereHas($parameter, function ($query) use ($value) {
                $query->whereRaw("MATCH(local_name, latin_name) AGAINST(? IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION)", [$value]);
            });
        }

        return $query;
    }
}