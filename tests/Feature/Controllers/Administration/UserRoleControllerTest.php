<?php

namespace Tests\Feature\Controllers\Administration;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Inertia\Testing\AssertableInertia;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserRoleControllerTest extends TestCase
{
    public function test_get_list_of_roles_for_authorized_user()
    {
        // Create a user with the necessary role and roles to access the users
        $creator = User::factory()->create();
        $creator->givePermissionTo('edit user');

        // create a normal user
        $user = User::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($creator)->get(route('administration.users.roles', $user->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Users/Roles')
                ->has('user', fn(AssertableInertia $page) => $page->where('data.id', $user->id))
                ->has('roles', Role::all()->count())
                ->has('currentRoles')
        );
    }

    public function test_get_list_of_roles_should_be_denied_for_unauthorized_user()
    {
        // Create a user with the necessary role and roles to access the users
        $creator = User::factory()->create();

        // create a normal user
        $user = User::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($creator)->get(route('administration.users.roles', $user->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_update_roles_method_for_authorized_user()
    {
        // Create a test creator
        $creator = User::factory()->create();

        // Grant the user permission to 'edit user'
        $creator->givePermissionTo('edit user');

        $user = User::factory()->create();

        // Simulate a request to update the user's roles
        $response = $this->actingAs($creator)->put(route('administration.users.roles.update', ['user' => $user->id]), [
            'currentRoles' => [1],
        ]);

        // Assert the response
        $response->assertRedirect(route('administration.users.show', $user->id));

        // Reload the user from the database
        $user->refresh();

        // Assert the updated roles
        $this->assertEquals(['content manager'], $user->getRoleNames()->toArray());
    }

    public function test_update_roles_method_for_unauthorized_user()
    {
        // Create a test creator
        $creator = User::factory()->create();

        // Create another user who does not have the required role
        $otherUser = User::factory()->create();

        // Simulate a request to update the user's roles with an unauthorized user
        $response = $this->actingAs($creator)->put(route('administration.users.roles.update', ['user' => $otherUser->id]), [
            'currentRoles' => [1],
        ]);

        // Assert that the response has a 403 status code (Forbidden)
        $response->assertStatus(Response::HTTP_FORBIDDEN);

        // Reload the user from the database
        $otherUser->refresh();

        // Assert that the user's roles remain unchanged
        $this->assertEmpty($otherUser->getRoleNames()->toArray());
    }
}