<?php

namespace Tests\Feature\Controllers\Administration;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class UserPasswordControllerTest extends TestCase
{
    public function test_edit_password_method_authorized()
    {
        // Create a creator
        $creator = User::factory()->create();

        // Assign the edit user permission to the creator
        $creator->givePermissionTo('edit user');

        // Create a user
        $user = User::factory()->create();

        // Perform the request
        $response = $this->actingAs($creator)->get(route('administration.users.password.edit', $user->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(function (AssertableInertia $page) use ($user) {
            $page->component('Admin/Users/EditPassword')
                ->has('user', function (AssertableInertia $page) use ($user) {
                    $page->where('data.id', $user->id);
                    $page->where('data.username', $user->username);
                });
        });
    }

    public function test_edit_password_method_unauthorized()
    {
        // Create a creator
        $creator = User::factory()->create();

        // Create a user
        $user = User::factory()->create();

        // Perform the request
        $response = $this->actingAs($creator)->get(route('administration.users.password.edit', $user->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_update_user_for_authorized_creator()
    {
        // Create a creator with the necessary role and permissions to edit users
        $creator = User::factory()->create();
        $creator->givePermissionTo('edit user');

        // Create a user for testing
        $user = User::factory()->create();

        // Simulate a request with the authenticated user and valid input data
        $response = $this->actingAs($creator)->put(route('administration.users.password.update', $user->id), [
            'password' => 'passwordd',
            'password_confirmation' => 'passwordd',
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.users.index'));
    }

    public function test_update_method_denied_for_unauthorized_user()
    {
        // Create a creator without the necessary role and permissions to edit users
        $creator = User::factory()->create();

        // Create a user for testing
        $user = User::factory()->create();

        // Simulate a request with the authenticated user and valid input data
        $response = $this->actingAs($creator)->put(route('administration.users.password.update', $user->id), [
            'password' => 'passwordd',
            'password_confirmation' => 'passwordd',
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}