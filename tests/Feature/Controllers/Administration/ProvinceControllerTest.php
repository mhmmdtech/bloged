<?php

namespace Tests\Feature\Controllers\Administration;

use App\Enums\ProvinceStatus;
use App\Models\Province;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia;

class ProvinceControllerTest extends TestCase
{
    public function test_index_method_returns_provinces_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to access the provinces
        $user = User::factory()->create();
        $user->givePermissionTo('browse province');

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.provinces.index'));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Provinces/Index')
                ->has('provinces')
        );
    }

    public function test_index_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to access the provinces
        $user = User::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.provinces.index'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_create_method_displays_form_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to add provinces
        $user = User::factory()->create();
        $user->givePermissionTo('add province');

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.provinces.create'));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Provinces/Create')
                ->has('statuses')
        );
    }

    public function test_create_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to add provinces
        $user = User::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.provinces.create'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_store_method_creates_province_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to add provinces
        $user = User::factory()->create();
        $user->givePermissionTo('add province');

        // Simulate a request with the authenticated user and valid input data
        $response = $this->actingAs($user)->post(route('administration.provinces.store'), [
            'local_name' => 'تهران',
            'latin_name' => 'Tehran',
            'status' => ProvinceStatus::Active->value,
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.provinces.index'));
        $this->assertDatabaseHas('provinces', [
            'local_name' => 'تهران',
            'latin_name' => 'Tehran',
        ]);
    }

    public function test_store_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to add provinces
        $user = User::factory()->create();

        // Simulate a request with the authenticated user and valid input data
        $response = $this->actingAs($user)->post(route('administration.provinces.store'), [
            'local_name' => 'تهران',
            'latin_name' => 'Tehran',
            'status' => ProvinceStatus::Active->value,
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertDatabaseMissing('provinces', [
            'local_name' => 'تهران',
            'latin_name' => 'Tehran',
        ]);
    }

    public function test_show_method_displays_province_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to read provinces
        $user = User::factory()->create();
        $user->givePermissionTo('read province');

        // Create a province for testing
        $province = Province::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.provinces.show', $province->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(function (AssertableInertia $page) use ($province) {
            $page->component('Admin/Provinces/Show')
                ->has('province', function (AssertableInertia $page) use ($province) {
                    $page->where('data.id', $province->id);
                    $page->where('data.local_name', $province->local_name);
                    $page->where('data.latin_name', $province->latin_name);
                });
        });
    }

    public function test_show_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to read provinces
        $user = User::factory()->create();

        // Create a province for testing
        $province = Province::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.provinces.show', $province->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_edit_method_displays_form_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to edit provinces
        $user = User::factory()->create();
        $user->givePermissionTo('edit province');

        // Create a province for testing
        $province = Province::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.provinces.edit', $province->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(function (AssertableInertia $page) use ($province) {
            $page->component('Admin/Provinces/Edit')
                ->has('province', function (AssertableInertia $page) use ($province) {
                    $page->where('data.id', $province->id);
                    $page->where('data.local_name', $province->local_name);
                    $page->where('data.latin_name', $province->latin_name);
                });
            $page->has('statuses');
        });
    }

    public function test_edit_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to edit provinces
        $user = User::factory()->create();

        // Create a province for testing
        $province = Province::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.provinces.edit', $province->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_update_method_updates_province_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to edit provinces
        $user = User::factory()->create();
        $user->givePermissionTo('edit province');

        // Create a province for testing
        $province = Province::factory()->create();

        // Simulate a request with the authenticated user and valid input data
        $response = $this->actingAs($user)->put(route('administration.provinces.update', $province->id), [
            'local_name' => 'تهران',
            'latin_name' => 'Tehran',
            'status' => ProvinceStatus::Active->value,
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.provinces.index'));
        $this->assertDatabaseHas('provinces', [
            'id' => $province->id,
            'local_name' => 'تهران',
            'latin_name' => 'Tehran',
        ]);
    }

    public function test_update_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to edit provinces
        $user = User::factory()->create();

        // Create a province for testing
        $province = Province::factory()->create();

        // Simulate a request with the authenticated user and valid input data
        $response = $this->actingAs($user)->put(route('administration.provinces.update', $province->id), [
            'local_name' => 'تهران',
            'latin_name' => 'Tehran',
            'status' => ProvinceStatus::Active->value,
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertDatabaseMissing('provinces', [
            'id' => $province->id,
            'local_name' => 'تهران',
            'latin_name' => 'Tehran',
        ]);
    }

    public function test_destroy_method_deletes_province_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete provinces
        $user = User::factory()->create();
        $user->givePermissionTo('delete province');

        // Create a province for testing
        $province = Province::factory()->create();

        // Simulate a request with the authenticated user and the province's ID in the URL
        $response = $this->actingAs($user)->delete(route('administration.provinces.destroy', $province->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.provinces.index'));
        $this->assertSoftDeleted('provinces', ['id' => $province->id]);
    }

    public function test_destroy_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to delete provinces
        $user = User::factory()->create();

        // Create a province for testing
        $province = Province::factory()->create();

        // Simulate a request with the authenticated user and the province's ID in the URL
        $response = $this->actingAs($user)->delete(route('administration.provinces.destroy', $province->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertDatabaseHas('provinces', ['id' => $province->id]);
    }

    public function test_trashed_method_displays_soft_deleted_provinces_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete provinces
        $user = User::factory()->create();
        $user->givePermissionTo('delete province');

        // Create some soft-deleted provinces for testing
        $provinces = Province::factory()->count(5)->create();
        foreach ($provinces as $province) {
            $province->delete();
        }

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.provinces.trashed'));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) =>
            $page->component('Admin/Provinces/Trashed')
                ->has('provinces.data', $provinces->count())
        );
    }

    public function test_trashed_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to delete provinces
        $user = User::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.provinces.trashed'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_forceDelete_method_deletes_all_soft_deleted_provinces_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete provinces
        $user = User::factory()->create();
        $user->givePermissionTo('delete province');

        // Create some soft-deleted provinces for testing
        $trashedProvinces = Province::factory()->count(5)->create();
        foreach ($trashedProvinces as $province) {
            $province->delete();
        }

        // Simulate a request with the authenticated user and no specific province
        $response = $this->actingAs($user)->delete(route('administration.provinces.force-delete'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.provinces.trashed'));
        $this->assertDatabaseMissing('provinces', ['deleted_at' => null]);
    }

    public function test_forceDelete_method_deletes_specific_soft_deleted_province_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete provinces
        $user = User::factory()->create();
        $user->givePermissionTo('delete province');

        // Create a soft-deleted province for testing
        $trashedProvince = Province::factory()->create();
        $trashedProvince->delete();

        // Simulate a request with the authenticated user and the specific province's ID
        $response = $this->actingAs($user)->delete(route('administration.provinces.force-delete', $trashedProvince->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.provinces.trashed'));
        $this->assertDatabaseMissing('provinces', ['id' => $trashedProvince->id]);
    }

    public function test_forceDelete_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to delete provinces
        $user = User::factory()->create();

        // Simulate a request with the authenticated user and no specific province
        $response = $this->actingAs($user)->delete(route('administration.provinces.force-delete'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_restore_method_restores_soft_deleted_province_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete provinces
        $user = User::factory()->create();
        $user->givePermissionTo('delete province');

        // Create a soft-deleted province for testing
        $trashedProvince = Province::factory()->create();
        $trashedProvince->delete();

        // Simulate a request with the authenticated user and the specific province's ID
        $response = $this->actingAs($user)->patch(route('administration.provinces.restore', $trashedProvince->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.provinces.trashed'));
        $this->assertDatabaseHas('provinces', ['id' => $trashedProvince->id, 'deleted_at' => null]);
    }

    public function test_restore_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to delete provinces
        $user = User::factory()->create();

        // Create a soft-deleted province for testing
        $trashedProvince = Province::factory()->create();
        $trashedProvince->delete();

        // Simulate a request with the authenticated user and the specific province's ID
        $response = $this->actingAs($user)->patch(route('administration.provinces.restore', $trashedProvince->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertDatabaseMissing('provinces', ['id' => $trashedProvince->id, 'deleted_at' => null]);
    }
}