<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected string $normalOrderedColumn = 'id';

    protected string $trashedOrderedColumn = 'deleted_at';

    protected int $applicationPaginatedItemsCount = 10;

    protected int $administrationPaginatedItemsCount = 5;
}