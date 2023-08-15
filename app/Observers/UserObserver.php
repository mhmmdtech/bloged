<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     */
    public function creating(User $user): void
    {
        $user->searchable_username = $user->username;
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updating" event.
     */
    public function updating(User $user): void
    {
        if ($user->isDirty('username')) {
            $user->searchable_username = $user->username;
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {

        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}