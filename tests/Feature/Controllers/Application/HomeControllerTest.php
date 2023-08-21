<?php

namespace Tests\Feature\Controllers\Application;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia;

class HomeControllerTest extends TestCase
{
    public function test_index_page_is_shown_successfully(): void
    {
        $response = $this->get(route('application.home'));

        $response->assertStatus(Response::HTTP_OK);

        $response->assertInertia(
            fn(AssertableInertia $page) =>
            $page->component('App/Home')
                ->hasAll('featuredPost', 'latestPosts', 'categories')
        );
    }

    public function test_home_url_should_be_permanently_redirect_to_index_page(): void
    {
        $response = $this->get('/home');

        $response->assertStatus(Response::HTTP_MOVED_PERMANENTLY);
    }
}