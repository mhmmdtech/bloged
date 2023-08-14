<?php

namespace App\Exports;

use App\Http\Resources\UserCollection;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\View\View;


class UsersExport implements FromView
{
    use Exportable;

    public function __construct(public Collection $users)
    {
        //
    }

    public function view(): View
    {
        $users = new UserCollection($this->users);
        return view('reports.users', compact('users'));
    }
}