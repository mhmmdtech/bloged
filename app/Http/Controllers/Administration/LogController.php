<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Resources\LogCollection;
use App\Http\Resources\LogResource;
use App\Models\Log;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('browse log', Log::class);

        $logs = new LogCollection(Log::with('actioner')->latest('id')->paginate(5));

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