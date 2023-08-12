<?php

namespace App\Http\Controllers\Administration;

use App\Enums\CategoryStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('browse category', Category::class);

        $categories = new CategoryCollection(Category::latest()->paginate(5));

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
    public function store(StoreCategoryRequest $request)
    {
        $this->authorize('add category', Category::class);

        $inputs = $request->validated();

        $inputs['thumbnail'] = Storage::disk('public')->putFile('categories', $request->file('thumbnail'));

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
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->authorize('edit category', $category);

        $inputs = removeNullFromArray($request->validated());

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
}