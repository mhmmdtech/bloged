<?php

namespace Tests\Feature\Controllers\Application;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia;

class PostControllerTest extends TestCase
{
    public function test_posts_page_is_shown_successfully(): void
    {
        $response = $this->get(route('application.posts.index'));

        $response->assertStatus(Response::HTTP_OK);

        $response->assertInertia(
            fn(AssertableInertia $page) =>
            $page->component('App/Posts/Index')
                ->has('posts')
        );
    }

    public function test_post_page_is_shown_successfully(): void
    {
        $author = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->for($author, 'author')->for($category, 'category')->create();

        $response = $this->get(route('application.posts.show', ['post' => $post->unique_id, 'slug' => $post->slug]));

        $response->assertStatus(Response::HTTP_OK);

        $response->assertInertia(
            fn(AssertableInertia $page) =>
            $page->component('App/Posts/Show')
                ->has('post', fn(AssertableInertia $page) =>
                    $page->where('data.id', $post->id)
                        ->where('data.unique_id', $post->unique_id))
        );
    }
}