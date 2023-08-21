<?php

namespace Tests\Feature\Controllers\Administration;

use App\Enums\CategoryStatus;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia;
use Illuminate\Http\UploadedFile;

class CategoryControllerTest extends TestCase
{
    public function test_index_method_displays_categories_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to browse categories
        $user = User::factory()->create();
        $user->givePermissionTo('browse category');

        // Create categories for testing
        $categories = Category::factory()->count(5)->create();

        // Simulate a GET request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.categories.index'));

        // Assert the response
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Categories/Index')
                ->has('categories')
        );
    }

    public function test_index_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to browse categories
        $user = User::factory()->create();

        // Simulate a GET request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.categories.index'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_create_method_displays_category_creation_form_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to add categories
        $user = User::factory()->create();
        $user->givePermissionTo('add category');

        // Simulate a GET request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.categories.create'));

        // Assert the response
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Categories/Create')
                ->has('statuses')
        );
    }

    public function test_create_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to add categories
        $user = User::factory()->create();

        // Simulate a GET request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.categories.create'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_store_method_creates_category_and_redirects_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to add categories
        $user = User::factory()->create();
        $user->givePermissionTo('add category');

        // Create a mock file for testing
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg', 320, 320)->size(1 * 1024);

        // Simulate a POST request with the authenticated user and form data
        $response = $this->actingAs($user)
            ->post(route('administration.categories.store'), [
                'title' => 'Test Category',
                'seo_title' => 'Test Category',
                'description' => 'Test Category Description',
                'seo_description' => 'Test Category',
                'status' => CategoryStatus::Active->value,
                'thumbnail' => $thumbnail,
            ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.categories.index'));

    }

    public function test_store_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to add categories
        $user = User::factory()->create();

        // Create a mock file for testing
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg', 320, 320)->size(1 * 1024);

        // Simulate a POST request with the authenticated user and form data
        $response = $this->actingAs($user)
            ->post(route('administration.categories.store'), [
                'title' => 'Test Category',
                'seo_title' => 'Test Category',
                'description' => 'Test Category Description',
                'seo_description' => 'Test Category',
                'status' => CategoryStatus::Active->value,
                'thumbnail' => $thumbnail,
            ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_store_method_validates_input_data()
    {
        // Create a user with the necessary role and permissions to add cities
        $user = User::factory()->create();
        $user->givePermissionTo('add category');

        // Simulate a POST request with the authenticated user and invalid input data
        $response = $this->actingAs($user)->post(route('administration.categories.store'), [
            // Missing required input fields
        ]);

        // Assert the response
        $response->assertSessionHasErrors([
            'title',
            'seo_title',
            'description',
            'seo_description',
            'status',
            'thumbnail',
        ]);
    }

    public function test_show_method_authorization()
    {
        // Create a user and a category
        $user = User::factory()->create();
        $category = Category::factory()->create();

        // Assign the read category permission to the user
        $user->givePermissionTo('read category');

        // Perform the request
        $response = $this->actingAs($user)->get(route('administration.categories.show', $category->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Categories/Show')
                ->has(
                    'category',
                    fn(AssertableInertia $page) =>
                    $page->where('data.id', $category->id)
                )
        );
    }

    public function test_show_method_unauthorized()
    {
        // Create a user and a category
        $user = User::factory()->create();
        $category = Category::factory()->create();

        // Perform the request
        $response = $this->actingAs($user)->get(route('administration.categories.show', $category->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_edit_method_authorization()
    {
        // Create a user and a category
        $user = User::factory()->create();
        $category = Category::factory()->create();

        // Assign the edit category permission to the user
        $user->givePermissionTo('edit category');

        // Perform the request
        $response = $this->actingAs($user)->get(route('administration.categories.edit', $category->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Categories/Edit')
                ->has(
                    'category',
                    fn(AssertableInertia $page) => $page->where('data.id', $category->id)
                )
                ->has('statuses')
        );
    }

    public function test_edit_method_unauthorized()
    {
        // Create a user and a category
        $user = User::factory()->create();
        $category = Category::factory()->create();

        // Perform the request
        $response = $this->actingAs($user)->get(route('administration.categories.edit', $category->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_update_method_updates_category_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to edit categories
        $user = User::factory()->create();
        $user->givePermissionTo('edit category');

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a mock file for testing
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg', 320, 320)->size(1 * 1024);

        // Simulate a request with the authenticated user and valid input data
        $response = $this->actingAs($user)->put(route('administration.categories.update', $category->unique_id), [
            'title' => 'Test Category',
            'seo_title' => 'Test Category',
            'description' => 'Test Category Description',
            'seo_description' => 'Test Category',
            'status' => CategoryStatus::Active->value,
            'thumbnail' => $thumbnail,
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_update_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to edit categories
        $user = User::factory()->create();

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a mock file for testing
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg', 320, 320)->size(1 * 1024);

        // Simulate a request with the authenticated user and valid input data
        $response = $this->actingAs($user)->put(route('administration.categories.update', $category->unique_id), [
            'title' => 'Updated Category Title',
            'seo_title' => 'Updated Category Title',
            'description' => 'Test Category Description',
            'seo_description' => 'Test Category',
            'status' => CategoryStatus::Active->value,
            'thumbnail' => $thumbnail,
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
            'title' => 'Updated Category Title',
            'seo_title' => 'Updated Category Title',
        ]);
    }

    public function test_destroy_method_deletes_category_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete categories
        $user = User::factory()->create();
        $user->givePermissionTo('delete category');

        // Create a category for testing
        $category = Category::factory()->create();

        // Simulate a request with the authenticated user and the category's ID in the URL
        $response = $this->actingAs($user)->delete(route('administration.categories.destroy', $category->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.categories.index'));
        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    public function test_destroy_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to delete categories
        $user = User::factory()->create();

        // Create a category for testing
        $category = Category::factory()->create();

        // Simulate a request with the authenticated user and the category's ID in the URL
        $response = $this->actingAs($user)->delete(route('administration.categories.destroy', $category->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_trashed_method_displays_soft_deleted_categories_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete categories
        $user = User::factory()->create();
        $user->givePermissionTo('delete category');

        // Create some soft-deleted categories for testing
        $categories = Category::factory()->count(5)->create();
        foreach ($categories as $province) {
            $province->delete();
        }

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.categories.trashed'));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) =>
            $page->component('Admin/Categories/Trashed')
                ->has('categories.data', $categories->count())
        );
    }

    public function test_trashed_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to delete categories
        $user = User::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.categories.trashed'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_forceDelete_method_deletes_all_soft_deleted_categories_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete categories
        $user = User::factory()->create();
        $user->givePermissionTo('delete category');

        // Create some soft-deleted categories for testing
        $trashedCategories = Category::factory()->count(5)->create();
        foreach ($trashedCategories as $category) {
            $category->delete();
        }

        // Simulate a request with the authenticated user and no specific category
        $response = $this->actingAs($user)->delete(route('administration.categories.force-delete'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.categories.trashed'));
        $this->assertDatabaseMissing('categories', ['deleted_at' => null]);
    }

    public function test_forceDelete_method_deletes_specific_soft_deleted_category_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete categories
        $user = User::factory()->create();
        $user->givePermissionTo('delete category');

        // Create a soft-deleted category for testing
        $trashedCategory = Category::factory()->create();
        $trashedCategory->delete();

        // Simulate a request with the authenticated user and the specific category's ID
        $response = $this->actingAs($user)->delete(route('administration.categories.force-delete', $trashedCategory->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.categories.trashed'));
        $this->assertDatabaseMissing('categories', ['id' => $trashedCategory->id]);
    }

    public function test_forceDelete_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to delete categories
        $user = User::factory()->create();

        // Simulate a request with the authenticated user and no specific province
        $response = $this->actingAs($user)->delete(route('administration.categories.force-delete'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_restore_method_restores_soft_deleted_category_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete categories
        $user = User::factory()->create();
        $user->givePermissionTo('delete category');

        // Create a soft-deleted category for testing
        $trashedCategory = Category::factory()->create();
        $trashedCategory->delete();

        // Simulate a request with the authenticated user and the specific province's ID
        $response = $this->actingAs($user)->patch(route('administration.categories.restore', $trashedCategory->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.categories.trashed'));
        $this->assertDatabaseHas('categories', ['id' => $trashedCategory->id, 'deleted_at' => null]);
    }

    public function test_restore_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to delete categories
        $user = User::factory()->create();

        // Create a soft-deleted category for testing
        $trashedCategory = Category::factory()->create();
        $trashedCategory->delete();

        // Simulate a request with the authenticated user and the specific category's ID
        $response = $this->actingAs($user)->patch(route('administration.categories.restore', $trashedCategory->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertDatabaseMissing('categories', ['id' => $trashedCategory->id, 'deleted_at' => null]);
    }
}