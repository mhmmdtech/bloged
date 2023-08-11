<?php

namespace App\Listeners;

use App\Events\UserModified;
use App\Models\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RegisterModificationLogInDatabase
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserModified $event): void
    {
        Log::create([
            'actioner_id' => $event->actioner_id,
            'action' => $event->action,
            'modelable_type' => $event->model,
            'modelable_id' => $event->model_id,
            'old_model' => $event->old_model,
            'new_model' => $event->new_model,
        ]);
    }
}