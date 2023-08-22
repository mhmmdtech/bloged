<?php

namespace Tests\Feature\Controllers\Administration;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class UserSearchControllerTest extends TestCase
{
    public function test_user_search_controller_authorizes_authorized_user()
    {
        // Create a test user
        $user = User::factory()->create();

        // Grant the user permission to 'browse user'
        $user->givePermissionTo('browse user');

        // Simulate a request to the UserSearchController
        $response = $this->actingAs($user)->get(route('administration.users.advanced-search'));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Users/AdvancedSearch')
                ->has('creators')
                ->etc()
        );
    }

    public function test_user_search_controller_denies_unauthorized_user()
    {
        // Create a test user
        $user = User::factory()->create();

        // Simulate a request to the UserSearchController with an unauthorized user
        $response = $this->actingAs($user)->get(route('administration.users.advanced-search'));

        // Assert that the response has a 403 status code (Forbidden)
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_user_search_controller_returns_results_with_allowed_inputs()
    {
        // Create a test user
        $user = User::factory()->create();

        // Grant the user permission to 'browse user'
        $user->givePermissionTo('browse user');

        // Create a user matching the search criteria
        $matchingUser = User::factory()->create([
            'username' => 'john_doe',
            'creator_id' => 1,
        ]);

        // Simulate a request to the UserSearchController with allowed inputs
        $response = $this->actingAs($user)->get(route('administration.users.advanced-search', [
            'username' => 'john_doe',
            'creator_id' => 1,
        ]));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Users/AdvancedSearch')
                ->has('results')
                ->has('creators')
                ->etc()
        );
    }
}