<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\Image\ImageService;
use App\Services\UniqueId\UniqueId;
use Illuminate\Support\Str;

class CategoryObserver
{
    public function __construct(private ImageService $imageService)
    {
        //
    }
    /**
     * Handle the Category "creating" event.
     */
    public function creating(Category $category): void
    {
        $category->slug = Str::slug($category->seo_title);
        $category->unique_id = (new UniqueId())($category->id);
    }

    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "updating" event.
     */
    public function updating(Category $category): void
    {
        if ($category->isDirty('seo_title')) {
            $category->slug = Str::slug($category->seo_title);
        }
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        //
    }
}