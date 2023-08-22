<?php

namespace Tests\Feature\Controllers\Administration;

use App\Enums\GenderStatus;
use App\Enums\MilitaryStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_index_method_returns_users_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to access the users
        $user = User::factory()->create();
        $user->givePermissionTo('browse user');

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.users.index'));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Users/Index')
                ->has('users')
        );
    }

    public function test_index_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to access the users
        $user = User::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.users.index'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_create_method_displays_form_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to add users
        $user = User::factory()->create();
        $user->givePermissionTo('add user');

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.users.create'));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Users/Create')
                ->hasAll(['genders', 'provinces', 'militaryStatuses'])
        );
    }

    public function test_create_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to add users
        $user = User::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.users.create'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_store_method_creates_user_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to add users
        $user = User::factory()->create();
        $user->givePermissionTo('add user');

        // Simulate a request with the authenticated user and valid input data
        $response = $this->actingAs($user)->post(route('administration.users.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'national_code' => '5309752331',
            'mobile_number' => '+989123456789',
            'gender' => GenderStatus::Male->value,
            'email' => 'test@example.com',
            'username' => 'mhmdmrkbti',
            'password' => 'password',
            'password_confirmation' => 'password',
            'military_status' => MilitaryStatus::Done->value,
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.users.index'));
        $this->assertDatabaseHas('users', [
            'national_code' => '5309752331',
            'mobile_number' => '+989123456789',
            'email' => 'test@example.com',
            'creator_id' => $user->id,
        ]);
    }

    public function test_store_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to add users
        $user = User::factory()->create();

        // Simulate a request with the authenticated user and valid input data
        $response = $this->actingAs($user)->post(route('administration.users.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'national_code' => '5309752331',
            'mobile_number' => '+989123456789',
            'gender' => GenderStatus::Male->value,
            'email' => 'test@example.com',
            'username' => 'mhmdmrkbti',
            'password' => 'password',
            'password_confirmation' => 'password',
            'military_status' => MilitaryStatus::Done->value,
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertDatabaseMissing('users', [
            'national_code' => '5309752331',
            'mobile_number' => '+989123456789',
            'email' => 'test@example.com',
            'creator_id' => $user->id,
        ]);
    }

    public function test_show_method_displays_user_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to read users
        $creator = User::factory()->create();
        $creator->givePermissionTo('read user');

        // Create a user for testing
        $user = User::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($creator)->get(route('administration.users.show', $user->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(function (AssertableInertia $page) use ($user) {
            $page->component('Admin/Users/Show')
                ->has('user', function (AssertableInertia $page) use ($user) {
                    $page->where('data.id', $user->id);
                    $page->where('data.username', $user->username);
                });
        });
    }

    public function test_show_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to read users
        $creator = User::factory()->create();

        // Create a user for testing
        $user = User::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($creator)->get(route('administration.users.show', $user->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_edit_method_displays_form_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to edit users
        $creator = User::factory()->create();
        $creator->givePermissionTo('edit user');

        // Create a user for testing
        $user = User::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($creator)->get(route('administration.users.edit', $user->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(function (AssertableInertia $page) use ($user) {
            $page->component('Admin/Users/Edit')
                ->has('user', function (AssertableInertia $page) use ($user) {
                    $page->where('data.id', $user->id);
                    $page->where('data.username', $user->username);
                });
            $page->hasAll('genders', 'militaryStatuses', 'provinces');
        });
    }

    public function test_edit_method_denied_for_unauthorized_user()
    {
        // Create a creator without the necessary role and permissions to edit users
        $creator = User::factory()->create();

        // Create a user for testing
        $user = User::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($creator)->get(route('administration.users.edit', $user->id));

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
        $response = $this->actingAs($creator)->put(route('administration.users.update', $user->id), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'national_code' => '5309752331',
            'mobile_number' => '+989123456789',
            'gender' => GenderStatus::Male->value,
            'email' => 'test@examplee.com',
            'username' => 'mhmdmrkbti',
            'password' => 'password',
            'password_confirmation' => 'password',
            'military_status' => MilitaryStatus::Done->value,
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'test@examplee.com',
        ]);
    }

    public function test_update_method_denied_for_unauthorized_user()
    {
        // Create a creator without the necessary role and permissions to edit users
        $creator = User::factory()->create();

        // Create a user for testing
        $user = User::factory()->create();

        // Simulate a request with the authenticated user and valid input data
        $response = $this->actingAs($creator)->put(route('administration.users.update', $user->id), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'national_code' => '5309752331',
            'mobile_number' => '+989123456789',
            'gender' => GenderStatus::Male->value,
            'email' => 'test@examplee.com',
            'username' => 'mhmdmrkbti',
            'password' => 'password',
            'password_confirmation' => 'password',
            'military_status' => MilitaryStatus::Done->value,
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertDatabaseMissing('users', [
            'email' => 'test@examplee.com',
        ]);
    }

    public function test_delete_method_deletes_specific_soft_deleted_user_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete users
        $creator = User::factory()->create();
        $creator->givePermissionTo('delete user');

        // Create a user for testing
        $user = User::factory()->create();
        $user->delete();

        // Simulate a request with the authenticated user and the specific province's ID
        $response = $this->actingAs($creator)->delete(route('administration.users.destroy', $user->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_delete_method_denied_for_unauthorized_user()
    {
        // Create a creator without the necessary role and permissions to delete users
        $creator = User::factory()->create();

        // Create a user for testing
        $user = User::factory()->create();

        // Simulate a request with the authenticated user and no specific province
        $response = $this->actingAs($creator)->delete(route('administration.users.destroy', $user->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }
}