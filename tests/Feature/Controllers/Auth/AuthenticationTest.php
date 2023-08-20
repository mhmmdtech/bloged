<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\Captcha\Captcha;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create(['username' => 'tester']);

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password',
            'captcha_code' => (new Captcha())->generateForTest()
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create(['username' => 'tester']);

        $this->post('/login', [
            'username' => $user->username,
            'password' => 'wrong-password',
            'captcha_code' => (new Captcha())->generateForTest()
        ]);

        $this->assertGuest();
    }
}