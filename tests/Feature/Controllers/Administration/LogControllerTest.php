<?php

namespace Tests\Feature\Controllers\Administration;

use App\Models\Category;
use App\Models\Log;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia;

class LogControllerTest extends TestCase
{
    public function test_index_method_returns_logs_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to access the logs
        $user = User::factory()->create();
        $user->givePermissionTo('browse log');

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.logs.index'));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Logs/Index')
                ->has('logs')
        );
    }

    public function test_index_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to access the logs
        $user = User::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.logs.index'));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_show_method_returns_log_for_authorized_user()
    {
        // Create a user with the necessary role and permissions to access a log
        $user = User::factory()->create();
        $user->givePermissionTo('read log');

        // Create a log
        $log = Log::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.logs.show', $log->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Logs/Show')
                ->has('log')
        );
    }

    public function test_show_method_denied_for_unauthorized_user()
    {
        // Create a user without the necessary role and permissions to access a log
        $user = User::factory()->create();

        // Create a log
        $log = Log::factory()->create();

        // Simulate a request with the authenticated user
        $response = $this->actingAs($user)->get(route('administration.logs.show', $log->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}