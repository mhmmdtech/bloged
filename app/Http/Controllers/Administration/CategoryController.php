<?php

namespace App\Http\Controllers\Administration;

use App\Enums\CategoryStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\Image\ImageService;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('browse category', Category::class);

        $categories = new CategoryCollection(Category::latest($this->normalOrderedColumn)->paginate($this->administrationPaginatedItemsCount));

        return Inertia::render('Admin/Categories/Index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('add category', Category::class);

        $statuses = CategoryStatus::array();

        return Inertia::render('Admin/Categories/Create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request, ImageService $imageService)
    {
        $this->authorize('add category', Category::class);

        $inputs = $request->validated();

        $imageService->setExclusiveDirectory('images');
        $imageService->setImageDirectory('categories' . DIRECTORY_SEPARATOR . 'thumbnails');
        $imageService->setImageName(Str::slug($inputs['seo_title']));
        $inputs['thumbnail'] = $imageService->createIndexAndSave($inputs['thumbnail']);

        auth()->user()->categories()->create($inputs);

        return redirect()->route('administration.categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $this->authorize('read category', $category);

        $category->load('creator');

        $category = new CategoryResource($category);

        return Inertia::render('Admin/Categories/Show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $this->authorize('edit category', $category);

        $statuses = CategoryStatus::array();

        $category = new CategoryResource($category);

        return Inertia::render('Admin/Categories/Edit', compact('category', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, ImageService $imageService, Category $category)
    {
        $this->authorize('edit category', $category);

        $inputs = removeNullFromArray($request->validated());

        if (isset($inputs['thumbnail'])) {
            $imageService->deleteIndex($category->thumbnail);
            $imageService->setExclusiveDirectory('images');
            $imageService->setImageDirectory('categories' . DIRECTORY_SEPARATOR . 'thumbnails');
            $imageService->setImageName(Str::slug($inputs['seo_title']));
            $inputs['thumbnail'] = $imageService->createIndexAndSave($inputs['thumbnail']);
        }

        $category->update($inputs);

        return redirect()->route('administration.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete category', $category);

        $category->delete();

        return redirect()->route('administration.categories.index');
    }

    /**
     * Display a listing of the soft deleted resource.
     */
    public function trashed()
    {
        $this->authorize('delete category', Category::class);

        $categories = new CategoryCollection(Category::onlyTrashed()->latest($this->trashedOrderedColumn)->paginate($this->administrationPaginatedItemsCount));

        return Inertia::render('Admin/Categories/Trashed', compact('categories'));
    }

    /**
     * force delete the specified resource from storage.
     */
    public function forceDelete(ImageService $imageService, ?Category $category = null)
    {
        $this->authorize('delete category', Category::class);

        if (is_null($category)) {
            $trashedCategories = Category::onlyTrashed()->get(['id', 'thumbnail']);
            $trashedCategories->each(function (Category $category) use ($imageService) {
                $imageService->deleteIndex($category->thumbnail);
            });
            Category::whereIn('id', array_flatten($trashedCategories->toArray()))->forceDelete();
            return redirect()->route('administration.categories.trashed');
        }

        $imageService->deleteIndex($category->thumbnail);
        $category->forceDelete();
        return redirect()->route('administration.categories.trashed');
    }

    /**
     * restore the specified resource from storage.
     */
    public function restore(Category $category)
    {
        $this->authorize('delete category', Category::class);
        $category->restore();
        return redirect()->route('administration.categories.trashed');
    }
}