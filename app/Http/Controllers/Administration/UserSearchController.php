<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Builder;

class UserSearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $this->authorize('browse user', User::class);

        $results = [];
        $allowedColumns = ['national_code', 'email', 'username', 'creator_id',];
        $userInputs = removeNullFromArray(request()->input());
        $allowedInputs = array_intersect_key($userInputs, array_flip($allowedColumns));

        $creators = User::whereHas('roles.permissions', function (Builder $query) {
            $query->where('name', 'add user');
        })->get(['id', 'username']);

        if (count($allowedInputs) > 0) {
            $results = new UserCollection(User::with('roles')->where($allowedInputs)->latest($this->normalOrderedColumn)->paginate($this->administrationPaginatedItemsCount));
            return Inertia::render('Admin/Users/AdvancedSearch', compact('results', 'creators'));
        }
        return Inertia::render('Admin/Users/AdvancedSearch', compact('creators'));
    }
}