<?php

namespace Tests\Feature\Controllers\Administration;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Inertia\Testing\AssertableInertia;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UserPermissionControllerTest extends TestCase
{
    public function test_get_list_of_permissions_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to access the users
        $creator = User::factory()->create();
        $creator->givePermissionTo('edit user');

        // create a normal user
        $user = User::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($creator)->get(route('administration.users.permissions', $user->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Users/Permissions')
                ->has('user', fn(AssertableInertia $page) => $page->where('data.id', $user->id))
                ->has('permissions', Permission::all()->count())
                ->has('currentPermissions')
        );
    }

    public function test_get_list_of_permissions_should_be_denied_for_unauthorized_user()
    {
        // Create a user with the necessary role and permissions to access the users
        $creator = User::factory()->create();

        // create a normal user
        $user = User::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($creator)->get(route('administration.users.permissions', $user->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }


    public function test_update_Permissions_method_for_authorized_user()
    {
        // Create a test creator
        $creator = User::factory()->create();

        // Grant the user permission to 'edit user'
        $creator->givePermissionTo('edit user');

        $user = User::factory()->create();

        // Simulate a request to update the user's permissions
        $response = $this->actingAs($creator)->put(route('administration.users.permissions.update', ['user' => $user->id]), [
            'currentPermissions' => [1, 2, 3, 4, 5],
        ]);

        // Assert the response
        $response->assertRedirect(route('administration.users.show', $user->id));

        // Reload the user from the database
        $user->refresh();

        // Assert the updated permissions
        $this->assertEquals([1, 2, 3, 4, 5], $user->getDirectPermissions()->pluck('id')->toArray());
    }

    public function test_update_permissions_method_for_unauthorized_user()
    {
        // Create a test creator
        $creator = User::factory()->create();

        // Create another user who does not have the required permission
        $otherUser = User::factory()->create();

        // Simulate a request to update the user's permissions with an unauthorized user
        $response = $this->actingAs($creator)->put(route('administration.users.permissions.update', ['user' => $otherUser->id]), [
            'currentPermissions' => [1, 2, 3, 4, 5],
        ]);

        // Assert that the response has a 403 status code (Forbidden)
        $response->assertStatus(Response::HTTP_FORBIDDEN);

        // Reload the user from the database
        $otherUser->refresh();

        // Assert that the user's permissions remain unchanged
        $this->assertEmpty($otherUser->getPermissionNames()->toArray());
    }
}