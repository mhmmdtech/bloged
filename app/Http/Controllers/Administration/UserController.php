<?php

namespace App\Http\Controllers\Administration;

use App\Enums\GenderStatus;
use App\Enums\MilitaryStatus;
use App\Events\UserModified;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserPasswordRequest;
use App\Http\Requests\Admin\UpdateUserPermissionsRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\UpdateUserRolesRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Province;
use App\Models\User;
use App\Services\Image\ImageService;
use Inertia\Inertia;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\UsersExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('browse user', User::class);

        $users = new UserCollection(User::with('roles')->latest()->paginate(5));

        return Inertia::render('Admin/Users/Index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('add user', User::class);

        $genders = GenderStatus::array();
        $militaryStatuses = MilitaryStatus::array();
        $provinces = Province::with('cities')->get(['id', 'local_name']);

        return Inertia::render('Admin/Users/Create', compact('genders', 'militaryStatuses', 'provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, ImageService $imageService)
    {
        $this->authorize('add user', User::class);

        $inputs = $request->validated();

        $inputs['mobile_number'] = convertToIrMobileFormat($inputs['mobile_number']);

        if ($inputs['gender'] != GenderStatus::Male->value)
            $inputs['military_status'] = null;

        DB::beginTransaction();

        try {
            $user = auth()->user()->users()->create($inputs);

            $user->verificationCodes()->create(['token' => generateRandomCode(5, 8)]);

            if (isset($inputs['avatar'])) {
                $imageService->setExclusiveDirectory('images');
                $imageService->setImageDirectory('users' . DIRECTORY_SEPARATOR . 'avatars');
                $imageService->setImageName($user->username);
                $user->avatar = $imageService->fitAndSave($inputs['avatar'], 400, 400);
                $user->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        event(new Registered($user));

        event(new UserModified(auth()->id(), 'create', User::class, $user->id, [], $user->toArray()));

        return redirect()->route('administration.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('read user', $user);

        $user->load('creator', 'province', 'city');

        $user = new UserResource($user);

        return Inertia::render('Admin/Users/Show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('edit user', $user);

        $user->load('province', 'city');

        $genders = GenderStatus::array();
        $militaryStatuses = MilitaryStatus::array();
        $provinces = Province::with('cities')->get(['id', 'local_name']);

        $user = new UserResource($user);

        return Inertia::render('Admin/Users/Edit', compact('user', 'genders', 'militaryStatuses', 'provinces'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, ImageService $imageService, User $user)
    {
        $this->authorize('edit user', $user);

        $inputs = removeNullFromArray($request->validated());

        $inputs['mobile_number'] = convertToIrMobileFormat($inputs['mobile_number']);

        if ($inputs['gender'] != GenderStatus::Male->value)
            $inputs['military_status'] = null;

        if (isset($inputs['avatar'])) {
            $imageService->deleteImage($request->user()->avatar);
            $imageService->setExclusiveDirectory('images');
            $imageService->setImageDirectory('users' . DIRECTORY_SEPARATOR . 'avatars');
            $imageService->setImageName($inputs['username']);
            $inputs['avatar'] = $imageService->fitAndSave($inputs['avatar'], 400, 400);
        }

        $oldUser = clone $user;
        $user->fill($inputs);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->verificationCodes()->create(['token' => generateRandomCode(5, 8)]);
        }

        $user->save();

        event(new UserModified(auth()->id(), 'update', User::class, $user->id, $oldUser->toArray(), $user->toArray()));

        return redirect()->route('administration.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete user', $user);

        $user->delete();

        event(new UserModified(auth()->id(), 'destroy', User::class, $user->id, $user->toArray(), []));

        return redirect()->route('administration.users.index');
    }

    /**
     * Display a listing of all roles.
     */
    public function roles(User $user)
    {
        $this->authorize('edit user', $user);

        $roles = Role::all();
        $user = new UserResource($user);
        $currentRoles = $user->getRoleNames()->toArray();

        return Inertia::render('Admin/Users/Roles', compact('user', 'roles', 'currentRoles'));
    }

    /**
     * Update the specified resource roles.
     */
    public function updateRoles(UpdateUserRolesRequest $request, User $user)
    {
        $this->authorize('edit user', $user);

        $inputs = $request->validated();

        $oldRoles = $user->getRoleNames();

        $user->syncRoles($inputs['currentRoles']);

        $newRoles = $user->getRoleNames();

        event(new UserModified(auth()->id(), 'update roles', User::class, $user->id, $oldRoles->toArray(), $newRoles->toArray()));

        return redirect()->route('administration.users.show', $user->id);
    }

    /**
     * Display a listing of all permissions.
     */
    public function permissions(User $user)
    {
        $this->authorize('edit user', $user);

        $permissions = Permission::all();
        $user = new UserResource($user);
        $currentPermissions = $user->getDirectPermissions()->pluck('name')->toArray();

        return Inertia::render('Admin/Users/Permissions', compact('user', 'permissions', 'currentPermissions'));
    }

    /**
     * Update the specified resource permissions.
     */
    public function updatePermissions(UpdateUserPermissionsRequest $request, User $user)
    {
        $this->authorize('edit user', $user);

        $inputs = $request->validated();

        $oldPermissions = $user->getPermissionNames();

        $user->syncPermissions($inputs['currentPermissions']);

        $newPermissions = $user->getPermissionNames();

        event(new UserModified(auth()->id(), 'update permissions', User::class, $user->id, $oldPermissions->toArray(), $newPermissions->toArray()));

        return redirect()->route('administration.users.show', $user->id);
    }

    /**
     * Show the form for editing the specified user password.
     */
    public function editPassword(User $user)
    {
        $this->authorize('edit user', $user);

        $user = new UserResource($user);

        return Inertia::render('Admin/Users/EditPassword', compact('user'));
    }

    /**
     * Update the specified user password in storage.
     */
    public function updatePassword(UpdateUserPasswordRequest $request, User $user)
    {
        $this->authorize('edit user', $user);

        $inputs = $request->validated();

        $user->update(['password' => $inputs['password']]);

        event(new UserModified(auth()->id(), 'update password', User::class, $user->id, $user->toArray(), $user->toArray()));

        return redirect()->route('administration.users.index');
    }

    /**
     * Display a listing of the searched resource.
     */
    public function advancedSearch()
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
            $results = new UserCollection(User::with('roles')->where($allowedInputs)->latest('id')->paginate(5));
            return Inertia::render('Admin/Users/AdvancedSearch', compact('results', 'creators'));
        }
        return Inertia::render('Admin/Users/AdvancedSearch', compact('creators'));
    }

    /**
     * Display a report based on province, city and gender parameters.
     */
    public function report()
    {
        $this->authorize('browse analytic', User::class);
        $results = [];
        $allowedColumns = ['province', 'city', 'gender',];
        $userInputs = removeNullFromArray(request()->input());
        $reportParameters = array_intersect_key($userInputs, array_flip($allowedColumns));
        $genders = GenderStatus::array();

        if (count($reportParameters) === 0) {
            return Inertia::render('Admin/Users/Report', compact('genders'));
        }

        $query = User::query();

        if (array_key_exists('gender', $reportParameters)) {
            $query->where('gender', request()->query('gender'));
        }

        if (array_key_exists('province', $reportParameters)) {
            $province = request()->query('province');
            $query->whereHas('province', function ($query) use ($province) {
                $query->where('local_name', $province)->orWhere('latin_name', $province);
            });
        }

        if (array_key_exists('city', $reportParameters)) {
            $city = request()->query('city');
            $query->whereHas('city', function ($query) use ($city) {
                $query->where('local_name', $city)->orWhere('latin_name', $city);
            });
        }

        $results = $query->paginate(5)->withQueryString();
        $results = new UserCollection($results);
        return Inertia::render('Admin/Users/Report', compact('results', 'genders'));
    }

    /**
     * handle downloading report file
     */
    public function downloadReport(string $format)
    {
        $this->authorize('browse analytic', User::class);

        $allowedFormats = ['print', 'pdf', 'excel', 'csv'];

        if (!in_array($format, $allowedFormats)) {
            return redirect()->route('administration.users.report', request()->query());
        }

        $allowedColumns = ['province', 'city', 'gender',];
        $userInputs = removeNullFromArray(request()->input());
        $reportParameters = array_intersect_key($userInputs, array_flip($allowedColumns));

        if (count($reportParameters) === 0) {
            return redirect()->route('administration.users.report');
        }

        $reportMethods = [
            'print' => 'printReport',
            'pdf' => 'pdfReport',
            'excel' => 'excelReport',
            'csv' => 'csvReport',
        ];

        $result = NULL;

        if (isset($reportMethods[$format])) {
            $methodName = $reportMethods[$format];
            $result = $this->$methodName($reportParameters);
        }

        return $result;
    }

    /**
     *  generate printable report file
     */
    private function printReport($reportParameters)
    {
        $query = User::query();

        if (array_key_exists('gender', $reportParameters)) {
            $query->where('gender', request()->query('gender'));
        }

        if (array_key_exists('province', $reportParameters)) {
            $province = request()->query('province');
            $query->whereHas('province', function ($query) use ($province) {
                $query->where('local_name', $province)->orWhere('latin_name', $province);
            });
        }

        if (array_key_exists('city', $reportParameters)) {
            $city = request()->query('city');
            $query->whereHas('city', function ($query) use ($city) {
                $query->where('local_name', $city)->orWhere('latin_name', $city);
            });
        }

        $users = $query->get();
        $users = new UserCollection($users);
        return Inertia::render('Admin/Users/PrintableReport', compact('users'));
    }

    /**
     *  genrate pdf report file
     */
    private function pdfReport($reportParameters)
    {
        $query = User::query();

        if (array_key_exists('gender', $reportParameters)) {
            $query->where('gender', request()->query('gender'));
        }

        if (array_key_exists('province', $reportParameters)) {
            $province = request()->query('province');
            $query->whereHas('province', function ($query) use ($province) {
                $query->where('local_name', $province)->orWhere('latin_name', $province);
            });
        }

        if (array_key_exists('city', $reportParameters)) {
            $city = request()->query('city');
            $query->whereHas('city', function ($query) use ($city) {
                $query->where('local_name', $city)->orWhere('latin_name', $city);
            });
        }
        $users = $query->get();
        $users = new UserCollection($users);
        $pdf = Pdf::loadView('reports.users', compact('users'));
        return $pdf->download('users-report.pdf');
    }

    /**
     *  genrate excel report file
     */
    private function excelReport($reportParameters)
    {
        // https://docs.laravel-excel.com/
        $query = User::query();

        if (array_key_exists('gender', $reportParameters)) {
            $query->where('gender', request()->query('gender'));
        }

        if (array_key_exists('province', $reportParameters)) {
            $province = request()->query('province');
            $query->whereHas('province', function ($query) use ($province) {
                $query->where('local_name', $province)->orWhere('latin_name', $province);
            });
        }

        if (array_key_exists('city', $reportParameters)) {
            $city = request()->query('city');
            $query->whereHas('city', function ($query) use ($city) {
                $query->where('local_name', $city)->orWhere('latin_name', $city);
            });
        }

        $result = $query->get();

        return (new UsersExport($result))->download('users.xlsx');
    }

    /**
     *  genrate csv report file
     */
    private function csvReport($reportParameters)
    {
        // https://github.com/vitorccs/laravel-csv
        $query = User::query();

        if (array_key_exists('gender', $reportParameters)) {
            $query->where('gender', request()->query('gender'));
        }

        if (array_key_exists('province', $reportParameters)) {
            $province = request()->query('province');
            $query->whereHas('province', function ($query) use ($province) {
                $query->where('local_name', $province)->orWhere('latin_name', $province);
            });
        }

        if (array_key_exists('city', $reportParameters)) {
            $city = request()->query('city');
            $query->whereHas('city', function ($query) use ($city) {
                $query->where('local_name', $city)->orWhere('latin_name', $city);
            });
        }

        $result = $query->get();

        return (new UsersExport($result))->download('users.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);

    }
}