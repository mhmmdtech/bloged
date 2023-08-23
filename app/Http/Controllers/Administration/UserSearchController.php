<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserSearchController extends Controller
{

    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

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

        $creators = $this->userRepository->getAllUsersWithSpecificPermission('add user');

        if (count($allowedInputs) > 0) {
            $results = new UserCollection(
                $this->userRepository->getUsersBySearchParams(
                    $allowedInputs,
                    $this->administrationPaginatedItemsCount,
                    $this->normalOrderedColumn
                )
            );

            return Inertia::render('Admin/Users/AdvancedSearch', compact('results', 'creators'));
        }

        return Inertia::render('Admin/Users/AdvancedSearch', compact('creators'));
    }
}