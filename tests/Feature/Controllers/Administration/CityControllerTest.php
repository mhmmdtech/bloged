<?php

namespace Tests\Feature\Controllers\Administration;

use App\Enums\CityStatus;
use App\Enums\ProvinceStatus;
use App\Models\City;
use App\Models\Province;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia;

class CityControllerTest extends TestCase
{
    public function test_index_method_displays_province_cities_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to browse cities
        $user = User::factory()->create();
        $user->givePermissionTo('browse city');

        // Create a province for testing with associated cities
        $province = Province::factory()->create();
        $cities = City::factory()->count(3)->create(['province_id' => $province->id]);

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.provinces.cities.index', $province->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Cities/Index')
                ->has(
                    'province',
                    fn(AssertableInertia $subPage) =>
                    $subPage->where('data.id', $province->id)
                )
                ->has('cities', $cities->count())
        );
    }

    public function test_index_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to browse cities
        $user = User::factory()->create();

        // Create a province for testing
        $province = Province::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.provinces.cities.index', $province->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_create_method_displays_create_form_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to add cities
        $user = User::factory()->create();
        $user->givePermissionTo('add city');

        // Create a province for testing
        $province = Province::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.provinces.cities.create', $province->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Cities/Create')
                ->has('statuses')
                ->has(
                    'province',
                    fn(AssertableInertia $subPage) =>
                    $subPage->where('data.id', $province->id)
                )
        );
    }

    public function test_create_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to add cities
        $user = User::factory()->create();

        // Create a province for testing
        $province = Province::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.provinces.cities.create', $province->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }


    public function test_store_method_stores_new_city_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to add cities
        $user = User::factory()->create();
        $user->givePermissionTo('add city');

        // Create a province for testing
        $province = Province::factory()->create();

        // Simulate a POST request with the authenticated user and valid input data
        $response = $this->actingAs($user)->post(route('administration.provinces.cities.store', $province->id), [
            'local_name' => 'تهران',
            'latin_name' => 'Tehran',
            'status' => CityStatus::Active->value,
        ]);

        // Assert the response
        $response->assertRedirect(route('administration.provinces.cities.index', $province->id));
        $response->assertStatus(Response::HTTP_FOUND);

        // Assert that the city was stored in the database
        $this->assertDatabaseHas('cities', [
            'province_id' => $province->id,
            'local_name' => 'تهران',
            'latin_name' => 'Tehran',
        ]);
    }

    public function test_store_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to add cities
        $user = User::factory()->create();

        // Create a province for testing
        $province = Province::factory()->create();

        // Simulate a POST request with the authenticated user and valid input data
        $response = $this->actingAs($user)->post(route('administration.provinces.cities.store', $province->id), [
            'local_name' => 'تهران',
            'latin_name' => 'Tehran',
            'status' => CityStatus::Active->value,
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);

        // Assert that the city was not stored in the database
        $this->assertDatabaseMissing('cities', [
            'province_id' => $province->id,
            'local_name' => 'تهران',
            'latin_name' => 'Tehran',
        ]);
    }

    public function test_store_method_validates_input_data()
    {
        // Create a user with the necessary role and permissions to add cities
        $user = User::factory()->create();
        $user->givePermissionTo('add city');

        // Create a province for testing
        $province = Province::factory()->create();

        // Simulate a POST request with the authenticated user and invalid input data
        $response = $this->actingAs($user)->post(route('administration.provinces.cities.store', $province->id), [
            // Missing required input fields
        ]);

        // Assert the response
        $response->assertSessionHasErrors([
            'local_name',
            'status',
        ]);

        // Assert that the city was not stored in the database
        $this->assertDatabaseMissing('cities', [
            'province_id' => $province->id,
        ]);
    }

    public function test_show_method_displays_city_details_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to read cities
        $user = User::factory()->create();
        $user->givePermissionTo('read city');

        // Create a province for testing
        $province = Province::factory()->create();

        // Create a city for testing
        $city = City::factory()->create();

        // Simulate a GET request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.cities.show', $city->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Cities/Show')
                ->has(
                    'city',
                    fn(AssertableInertia $subPage) =>
                    $subPage->where('data.id', $city->id)
                )
        );
    }

    public function test_show_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to read cities
        $user = User::factory()->create();

        // Create a province for testing
        $province = Province::factory()->create();

        // Create a city for testing
        $city = City::factory()->create();

        // Simulate a GET request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.cities.show', $city->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_edit_method_displays_city_edit_form_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to edit cities
        $user = User::factory()->create();
        $user->givePermissionTo('edit city');

        // Create a province for testing
        $province = Province::factory()->create();

        // Create a city for testing
        $city = City::factory()->create();

        // Simulate a GET request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.cities.edit', $city->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Cities/Edit')
                ->has(
                    'city',
                    fn(AssertableInertia $subPage) =>
                    $subPage->where('data.id', $city->id)
                )
                ->has('statuses')
        );
    }

    public function test_edit_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to edit cities
        $user = User::factory()->create();

        // Create a province for testing
        $province = Province::factory()->create();

        // Create a city for testing
        $city = City::factory()->create();

        // Simulate a GET request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.cities.edit', $city->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_update_method_updates_city_and_redirects_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to edit cities
        $user = User::factory()->create();
        $user->givePermissionTo('edit city');

        // Create a province for testing
        $province = Province::factory()->create();

        // Create a city for testing
        $city = City::factory()->create();

        // Simulate a PUT request with the authenticated user and updated city data
        $response = $this->actingAs($user)->put(route('administration.cities.update', $city->id), [
            'local_name' => 'تهران',
            'latin_name' => 'Tehran',
            'status' => ProvinceStatus::Active->value,
        ]);

        // Assert the response
        $response->assertRedirect(route('administration.cities.show', $city->id));
        $response->assertStatus(Response::HTTP_FOUND);
        // You can also add additional assertions to verify the city was updated correctly
    }

    public function test_update_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to edit cities
        $user = User::factory()->create();

        // Create a province for testing
        $province = Province::factory()->create();

        // Create a city for testing
        $city = City::factory()->create();

        // Simulate a PUT request with the authenticated user and updated city data
        $response = $this->actingAs($user)->put(route('administration.cities.update', $city->id), [
            'local_name' => 'تهران',
            'latin_name' => 'Tehran',
            'status' => ProvinceStatus::Active->value,
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_update_method_validates_required_fields()
    {
        // Create a user with the necessary role and permissions to edit cities
        $user = User::factory()->create();
        $user->givePermissionTo('edit city');

        // Create a province for testing
        $province = Province::factory()->create();

        // Create a city for testing
        $city = City::factory()->create();

        // Simulate a PUT request with the authenticated user and missing required fields
        $response = $this->actingAs($user)->put(route('administration.cities.update', $city->id), []);

        // Assert the response contains validation errors for the required fields
        $response->assertSessionHasErrors(['local_name', 'status']);
    }

    public function test_update_method_validates_string_length()
    {
        // Create a user with the necessary role and permissions to edit cities
        $user = User::factory()->create();
        $user->givePermissionTo('edit city');

        // Create a province for testing
        $province = Province::factory()->create();

        // Create a city for testing
        $city = City::factory()->create();

        // Simulate a PUT request with the authenticated user and invalid string lengths
        $response = $this->actingAs($user)->put(route('administration.cities.update', $city->id), [
            'local_name' => 'A',
            // Below the minimum length
            'latin_name' => 'This string is too long and exceeds the maximum length limit. This string is too long and exceeds the maximum length limit',
            // Exceeds the maximum length
            'status' => ProvinceStatus::Active->value,
        ]);

        // Assert the response contains validation errors for the string length
        $response->assertSessionHasErrors(['local_name', 'latin_name']);
    }

    public function test_update_method_validates_enum()
    {
        // Create a user with the necessary role and permissions to edit cities
        $user = User::factory()->create();
        $user->givePermissionTo('edit city');

        // Create a province for testing
        $province = Province::factory()->create();

        // Create a city for testing
        $city = City::factory()->create();

        // Simulate a PUT request with the authenticated user and invalid status value
        $response = $this->actingAs($user)->put(route('administration.cities.update', $city->id), [
            'local_name' => 'تهران',
            'latin_name' => 'Tehran',
            'status' => 'invalid_status',
        ]);

        // Assert the response contains validation errors for the enum field
        $response->assertSessionHasErrors('status');
    }

    public function test_destroy_method_deletes_city_and_redirects_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete cities
        $user = User::factory()->create();
        $user->givePermissionTo('delete city');

        // Create a province for testing
        $province = Province::factory()->create();

        // Create a city for testing
        $city = City::factory()->create();

        // Simulate a DELETE request with the authenticated user
        $response = $this->actingAs($user)->delete(route('administration.cities.destroy', $city->id));

        // Assert the response
        $response->assertRedirect(route('administration.provinces.index'));
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_destroy_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to delete cities
        $user = User::factory()->create();

        // Create a province for testing
        $province = Province::factory()->create();

        // Create a city for testing
        $city = City::factory()->create();

        // Simulate a DELETE request with the authenticated user
        $response = $this->actingAs($user)->delete(route('administration.cities.destroy', $city->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}