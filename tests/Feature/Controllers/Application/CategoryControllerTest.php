<?php

namespace Tests\Feature\Controllers\Application;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia;

class CategoryControllerTest extends TestCase
{
    public function test_categories_page_is_shown_successfully(): void
    {
        $response = $this->get(route('application.categories.index'));

        $response->assertStatus(Response::HTTP_OK);

        $response->assertInertia(
            fn(AssertableInertia $page) =>
            $page->component('App/Categories/Index')
                ->has('categories')
        );
    }

    public function test_category_page_is_shown_successfully(): void
    {
        $creator = User::factory()->create();
        $category = Category::factory()->for($creator, 'creator')->hasPosts(5)->create();

        $response = $this->get(route('application.categories.show', ['category' => $category->unique_id, 'slug' => $category->slug]));

        $response->assertStatus(Response::HTTP_OK);

        $response->assertInertia(
            fn(AssertableInertia $page) =>
            $page->component('App/Categories/Single')
                ->has('category', fn(AssertableInertia $page) =>
                    $page->where('data.id', $category->id)
                        ->where('data.unique_id', $category->unique_id))
                ->has('posts')
        );
    }
}