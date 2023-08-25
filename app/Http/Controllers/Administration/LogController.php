<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Resources\LogCollection;
use App\Http\Resources\LogResource;
use App\Repositories\LogRepositoryInterface;
use App\Models\Log;
use Inertia\Inertia;

class LogController extends Controller
{

    public function __construct(
        private LogRepositoryInterface $logRepository
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('browse log', Log::class);

        $logs = new LogCollection($this->logRepository->getPaginatedLogs($this->administrationPaginatedItemsCount, $this->normalOrderedColumn));

        return Inertia::render('Admin/Logs/Index', compact('logs'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Log $log)
    {
        $this->authorize('read log', $log);

        $log->load('actioner');

        $log = new LogResource($log);

        return Inertia::render('Admin/Logs/Show', compact('log'));
    }
}