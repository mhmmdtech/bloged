<?php

namespace Tests\Feature\Controllers\Administration;

use App\Enums\GenderStatus;
use App\Models\City;
use App\Models\Province;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class UserReportControllerTest extends TestCase
{
    public function test_report_method_authorizes_authorized_user()
    {
        // Create a test user
        $user = User::factory()->create();

        // Grant the user permission to 'browse analytic' for User model
        $user->givePermissionTo('browse analytic');

        // Simulate a request to the report method
        $response = $this->actingAs($user)->get(route('administration.users.report'));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Users/Report')
                ->has('genders')
                ->etc()
        );
    }

    public function test_report_method_denies_unauthorized_user()
    {
        // Create a test user
        $user = User::factory()->create();

        // Simulate a request to the report method with an unauthorized user
        $response = $this->actingAs($user)->get(route('administration.users.report'));

        // Assert that the response has a 403 status code (Forbidden)
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_report_method_returns_report_with_parameters()
    {
        // Create a test user
        $user = User::factory()->create();

        // Grant the user permission to 'browse analytic' for User model
        $user->givePermissionTo('browse analytic');

        // Create provinces and cities for testing
        $province = Province::factory()->create();
        $city = City::factory()->create(['province_id' => $province->id]);

        // Create a user matching the report parameters
        $matchingUser = User::factory()->create([
            'gender' => GenderStatus::Female->value,
            'province_id' => $province->id,
            'city_id' => $city->id,
        ]);

        // Simulate a request to the report method with parameters
        $response = $this->actingAs($user)->get(route('administration.users.report', [
            'province' => $province->local_name,
            'city' => $city->local_name,
            'gender' => GenderStatus::Female->value,
        ]));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertInertia(
            fn(AssertableInertia $page) => $page
                ->component('Admin/Users/Report')
                ->has('results')
                ->has('genders')
                ->etc()
        );
    }
}