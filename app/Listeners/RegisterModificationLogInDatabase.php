<?php

namespace App\Listeners;

use App\Events\UserModified;
use App\Models\Log;
use App\Repositories\LogRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RegisterModificationLogInDatabase
{
    /**
     * Create the event listener.
     */
    public function __construct(private LogRepository $logRepository)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserModified $event): void
    {
        $data = [
            'actioner_id' => $event->actioner_id,
            'action' => $event->action,
            'model_type' => $event->model,
            'model_id' => $event->model_id,
            'old_model' => $event->old_model,
            'new_model' => $event->new_model,
        ];

        $this->logRepository->create($data);
    }
}