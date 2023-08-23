<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\UniqueId\UniqueId;
use Illuminate\Support\Str;

class PostObserver
{
    /**
     * Handle the Post "creating" event.
     */
    public function creating(Post $post): void
    {
        $post->reading_time = estimateReadingTime($post->body);
        $post->slug = Str::slug($post->seo_title);
        $post->unique_id = (new UniqueId())($post->id);
    }

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "updating" event.
     */
    public function updating(Post $post): void
    {
        if ($post->isDirty('body')) {
            $post->reading_time = estimateReadingTime($post->body);
        }

        if ($post->isDirty('seo_title')) {
            $post->slug = Str::slug($post->seo_title);
        }
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }
}