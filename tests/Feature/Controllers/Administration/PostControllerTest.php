<?php

namespace Tests\Feature\Controllers\Administration;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia;
use Illuminate\Http\UploadedFile;

class PostControllerTest extends TestCase
{
    public function test_index_method_authorization()
    {
        // Create a user
        $user = User::factory()->create();

        // Assign the browse post permission to the user
        $user->givePermissionTo('browse post');

        // Perform the request
        $response = $this->actingAs($user)->get(route('administration.posts.index'));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Posts/Index')
                ->has('posts')
        );
    }

    public function test_index_method_unauthorized()
    {
        // Create a user
        $user = User::factory()->create();

        // Perform the request
        $response = $this->actingAs($user)->get(route('administration.posts.index'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_create_method_authorization()
    {
        // Create a user
        $user = User::factory()->create();

        // Assign the add post permission to the user
        $user->givePermissionTo('add post');

        // Perform the request
        $response = $this->actingAs($user)->get(route('administration.posts.create'));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Posts/Create')
                ->has('statuses')
                ->has('activeCategories')
        );
    }

    public function test_create_method_unauthorized()
    {
        // Create a user
        $user = User::factory()->create();

        // Perform the request
        $response = $this->actingAs($user)->get(route('administration.posts.create'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_store_method_stores_new_post_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to add posts
        $user = User::factory()->create();
        $user->givePermissionTo('add post');

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a post for testing
        $post = Post::factory()->create();

        // Create a mock file for testing
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg', 320, 320)->size(1 * 1024);

        // Simulate a POST request with the authenticated user and valid input data
        $response = $this->actingAs($user)->post(route('administration.posts.store', $post->unique_id), [
            'thumbnail' => $thumbnail,
            'title' => 'Test Post',
            'seo_title' => 'Test Post',
            'description' => 'Test Post Description',
            'seo_description' => 'Test Post',
            'status' => PostStatus::Published->value,
            'body' => fake()->realText(300),
            'html_content' => fake()->realText(300),
            'category_id' => $category->id,
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.posts.index'));

        // Assert that the post was stored in the database
        $this->assertDatabaseHas('posts', [
            'category_id' => $category->id,
            'title' => 'Test Post',
            'seo_title' => 'Test Post',
        ]);
    }

    public function test_store_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to add posts
        $user = User::factory()->create();

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a post for testing
        $post = Post::factory()->create();

        // Create a mock file for testing
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg', 320, 320)->size(1 * 1024);

        // Simulate a POST request with the authenticated user and valid input data
        $response = $this->actingAs($user)->post(route('administration.posts.store', $post->unique_id), [
            'thumbnail' => $thumbnail,
            'title' => 'Test Post',
            'seo_title' => 'Test Post',
            'description' => 'Test Post Description',
            'seo_description' => 'Test Post',
            'status' => PostStatus::Published->value,
            'body' => fake()->realText(300),
            'html_content' => fake()->realText(300),
            'category_id' => $category->id,
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);

        // Assert that the city was not stored in the database
        $this->assertDatabaseMissing('posts', [
            'category_id' => $category->id,
            'title' => 'Test Post',
            'seo_title' => 'Test Post',
        ]);
    }

    public function test_store_method_validates_input_data()
    {
        // Create a user without the necessary role and permissions to add posts
        $user = User::factory()->create();
        $user->givePermissionTo('add post');

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a post for testing
        $post = Post::factory()->create();

        // Simulate a POST request with the authenticated user and invalid input data
        $response = $this->actingAs($user)->post(route('administration.posts.store', $post->unique_id), [
            // Missing required input fields
        ]);

        // Assert the response
        $response->assertSessionHasErrors([
            'thumbnail',
            'title',
            'seo_title',
            'description',
            'seo_description',
            'status',
            'body',
            'html_content',
            'category_id'
        ]);

        // Assert that the post was not stored in the database
        $this->assertDatabaseMissing('posts', [
            'category_id' => $category->id,
            'title' => 'Test Post',
            'seo_title' => 'Test Post',
        ]);
    }

    public function test_show_method_displays_post_details_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to read posts
        $user = User::factory()->create();
        $user->givePermissionTo('read post');

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a post for testing
        $post = Post::factory()->create();

        // Simulate a GET request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.posts.show', $post->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Posts/Show')
                ->has(
                    'post',
                    fn(AssertableInertia $subPage) =>
                    $subPage->where('data.id', $post->id)
                )
        );
    }

    public function test_show_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to read posts
        $user = User::factory()->create();

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a post for testing
        $post = Post::factory()->create();

        // Simulate a GET request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.posts.show', $post->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_edit_method_authorization()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a post
        $post = Post::factory()->create();

        // Assign the edit post permission to the user
        $user->givePermissionTo('edit post');

        // Perform the request
        $response = $this->actingAs($user)->get(route('administration.posts.edit', $post->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Posts/Edit')
                ->has(
                    'post',
                    fn(AssertableInertia $subPage) =>
                    $subPage->where('data.id', $post->id)
                )
                ->has('statuses')
                ->has('activeCategories')
        );
    }

    public function test_edit_method_unauthorized()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a post
        $post = Post::factory()->create();

        // Perform the request
        $response = $this->actingAs($user)->get(route('administration.posts.edit', $post->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_update_method_updates_post_and_redirects_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to edit posts
        $user = User::factory()->create();
        $user->givePermissionTo('edit post');

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a post for testing
        $post = Post::factory()->create();

        // Create a mock file for testing
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg', 320, 320)->size(1 * 1024);

        // Simulate a PUT request with the authenticated user and updated post data
        $response = $this->actingAs($user)->put(route('administration.posts.update', $post->unique_id), [
            'thumbnail' => $thumbnail,
            'title' => 'Test Post',
            'seo_title' => 'Test Post',
            'description' => 'Test Post Description',
            'seo_description' => 'Test Post',
            'status' => PostStatus::Published->value,
            'body' => fake()->realText(300),
            'html_content' => fake()->realText(300),
            'category_id' => $category->id,
        ]);

        // Assert the response
        $response->assertRedirect(route('administration.posts.index'));
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_update_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to edit cities
        $user = User::factory()->create();

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a post for testing
        $post = Post::factory()->create();

        // Create a mock file for testing
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg', 320, 320)->size(1 * 1024);

        // Simulate a PUT request with the authenticated user and updated post data
        $response = $this->actingAs($user)->put(route('administration.posts.update', $post->unique_id), [
            'thumbnail' => $thumbnail,
            'title' => 'Test Post',
            'seo_title' => 'Test Post',
            'description' => 'Test Post Description',
            'seo_description' => 'Test Post',
            'status' => PostStatus::Published->value,
            'body' => fake()->realText(300),
            'html_content' => fake()->realText(300),
            'category_id' => $category->id,
        ]);

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_update_method_validates_required_fields()
    {
        // Create a user with the necessary role and permissions to edit posts
        $user = User::factory()->create();
        $user->givePermissionTo('edit post');

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a post for testing
        $post = Post::factory()->create();

        // Simulate a PUT request with the authenticated user and missing required fields
        $response = $this->actingAs($user)->put(route('administration.posts.update', $post->unique_id), []);

        // Assert the response contains validation errors for the required fields
        $response->assertSessionHasErrors([
            'title',
            'seo_title',
            'description',
            'seo_description',
            'status',
            'body',
            'html_content',
            'category_id'
        ]);
    }

    public function test_update_method_validates_string_length()
    {
        // Create a user with the necessary role and permissions to edit posts
        $user = User::factory()->create();
        $user->givePermissionTo('edit post');

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a post for testing
        $post = Post::factory()->create();

        // Create a mock file for testing
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg', 320, 320)->size(1 * 1024);

        // Simulate a PUT request with the authenticated user and invalid string lengths
        $response = $this->actingAs($user)->put(route('administration.posts.update', $post->unique_id), [
            'thumbnail' => $thumbnail,
            'title' => 'A',
            'seo_title' => 'A',
            'description' => fake()->realText(300),
            'seo_description' => fake()->realText(300),
            'status' => PostStatus::Published->value,
            'body' => fake()->realText(100),
            'html_content' => fake()->realText(100),
            'category' => $category->id,
        ]);

        // Assert the response contains validation errors for the string length
        $response->assertSessionHasErrors([
            'title',
            'seo_title',
            'description',
            'seo_description',
            'body',
            'html_content',
        ]);
    }

    public function test_update_method_validates_enum()
    {
        // Create a user with the necessary role and permissions to edit posts
        $user = User::factory()->create();
        $user->givePermissionTo('edit post');

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a post for testing
        $post = Post::factory()->create();

        // Create a mock file for testing
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg', 320, 320)->size(1 * 1024);

        // Simulate a PUT request with the authenticated user and invalid status value
        $response = $this->actingAs($user)->put(route('administration.posts.update', $post->unique_id), [
            'thumbnail' => $thumbnail,
            'title' => 'Test Post',
            'seo_title' => 'Test Post',
            'description' => 'Test Post Description',
            'seo_description' => 'Test Post',
            'status' => 'invalid_status',
            'body' => fake()->realText(300),
            'html_content' => fake()->realText(300),
            'category_id' => $category->id,
        ]);

        // Assert the response contains validation errors for the enum field
        $response->assertSessionHasErrors('status');
    }

    public function test_destroy_method_deletes_post_and_redirects_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete posts
        $user = User::factory()->create();
        $user->givePermissionTo('delete post');

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a post for testing
        $post = Post::factory()->create();

        // Simulate a DELETE request with the authenticated user
        $response = $this->actingAs($user)->delete(route('administration.posts.destroy', $post->unique_id));

        // Assert the response
        $response->assertRedirect(route('administration.posts.index'));
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_destroy_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to delete posts
        $user = User::factory()->create();

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a post for testing
        $post = Post::factory()->create();

        // Simulate a DELETE request with the authenticated user
        $response = $this->actingAs($user)->delete(route('administration.posts.destroy', $post->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_trashed_method_displays_soft_deleted_posts_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete categories
        $user = User::factory()->create();
        $user->givePermissionTo('delete post');

        // Create a category for testing
        $category = Category::factory()->create();

        // Create some soft-deleted posts for testing
        $posts = Post::factory()->count(5)->create();
        foreach ($posts as $post) {
            $post->delete();
        }

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.posts.trashed'));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) =>
            $page->component('Admin/Posts/Trashed')
                ->has('posts.data', $posts->count())
        );
    }

    public function test_trashed_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to delete posts
        $user = User::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.posts.trashed'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_forceDelete_method_deletes_all_soft_deleted_posts_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete posts
        $user = User::factory()->create();
        $user->givePermissionTo('delete post');

        // Create a category for testing
        $category = Category::factory()->create();

        // Create some soft-deleted posts for testing
        $trashedPosts = Post::factory()->count(5)->create();
        foreach ($trashedPosts as $post) {
            $post->delete();
        }

        // Simulate a request with the authenticated user and no specific category
        $response = $this->actingAs($user)->delete(route('administration.posts.force-delete'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.posts.trashed'));
        $this->assertDatabaseMissing('posts', ['deleted_at' => null]);
    }

    public function test_forceDelete_method_deletes_specific_soft_deleted_post_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete posts
        $user = User::factory()->create();
        $user->givePermissionTo('delete post');

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a soft-deleted post for testing
        $trashedPost = Post::factory()->create();
        $trashedPost->delete();

        // Simulate a request with the authenticated user and the specific category's ID
        $response = $this->actingAs($user)->delete(route('administration.posts.force-delete', $trashedPost->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.posts.trashed'));
        $this->assertDatabaseMissing('posts', ['id' => $trashedPost->id]);
    }

    public function test_forceDelete_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to delete posts
        $user = User::factory()->create();

        // Simulate a request with the authenticated user and no specific province
        $response = $this->actingAs($user)->delete(route('administration.posts.force-delete'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_restore_method_restores_soft_deleted_post_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to delete posts
        $user = User::factory()->create();
        $user->givePermissionTo('delete post');

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a soft-deleted post for testing
        $trashedPost = Post::factory()->create();
        $trashedPost->delete();

        // Simulate a request with the authenticated user and the specific province's ID
        $response = $this->actingAs($user)->patch(route('administration.posts.restore', $trashedPost->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('administration.posts.trashed'));
        $this->assertDatabaseHas('posts', ['id' => $trashedPost->id, 'deleted_at' => null]);
    }

    public function test_restore_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to delete posts
        $user = User::factory()->create();

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a soft-deleted post for testing
        $trashedPost = Post::factory()->create();
        $trashedPost->delete();

        // Simulate a request with the authenticated user and the specific post's ID
        $response = $this->actingAs($user)->patch(route('administration.posts.restore', $trashedPost->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertDatabaseMissing('posts', ['id' => $trashedPost->id, 'deleted_at' => null]);
    }

    public function test_toggle_featured_method_authorized()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a featured post
        $featuredPost = Post::factory()->create([
            'is_featured' => true,
        ]);

        // Create a non-featured post
        $nonFeaturedPost = Post::factory()->create([
            'is_featured' => false,
        ]);

        // Assign the edit post permission to the user
        $user->givePermissionTo('edit post');

        // Perform the request
        $response = $this->actingAs($user)->patch(route('administration.posts.toggle-featured', $nonFeaturedPost->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);

        $this->assertTrue(boolval($nonFeaturedPost->fresh()->is_featured));
        $this->assertFalse(boolval($featuredPost->fresh()->is_featured));
    }

    public function test_toggle_featured_method_unauthorized()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a category for testing
        $category = Category::factory()->create();

        // Create a non-featured post
        $post = Post::factory()->create([
            'is_featured' => false,
        ]);

        // Perform the request
        $response = $this->actingAs($user)->patch(route('administration.posts.toggle-featured', $post->unique_id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertFalse(boolval($post->fresh()->is_featured));
    }
}