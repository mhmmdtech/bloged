<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use App\Models\UserVerificationCode;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia;

class EmailVerificationCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_method_marks_email_as_verified_and_redirects_when_code_matches()
    {
        // Create a user with an unverified email
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // Generate a verification code for the user
        $verificationCode = UserVerificationCode::factory()->create([
            'user_id' => $user->id,
        ]);

        // Simulate a request with the authenticated user and the correct verification code
        $response = $this->actingAs($user)->post(route('verification-code.verify'), [
            'token' => $verificationCode->token,
        ]);

        // Assert the email is marked as verified and a redirection to the intended route with the "verified=1" query parameter
        $this->assertNotNull($user->fresh()->email_verified_at);
        $response->assertRedirect(RouteServiceProvider::HOME . '?verified=1');
    }
}