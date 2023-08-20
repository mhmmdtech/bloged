<?php

namespace Tests\Feature\Controllers\Application;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_returns_empty_when_no_query_parameter_available(): void
    {
        // Make the search request without a query parameter
        $response = $this->get('/search');

        // Assert the response
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('App/Search')
                ->missingAll('posts', 'auery')
                ->etc()
        );
    }

    public function test_search_returns_empty_when_query_length_less_is_than_5()
    {
        // Make the search request with a query parameter of length less than 5
        $response = $this->get('/search?query=abc');

        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_search_returns_matching_posts_and_categories()
    {
        // Make the search request
        $response = $this->get('/search?query=Matching');

        // Assert the response
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('App/Search')
                ->has(
                    'posts'
                )
                ->has('query')
                ->where(
                    'query',
                    "Matching"
                )
        );
    }
}