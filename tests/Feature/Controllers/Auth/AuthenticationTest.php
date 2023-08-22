<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Services\Captcha\Captcha;
use App\Services\Captcha\CaptchaRepository;

class AuthenticationTest extends TestCase
{
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create(['username' => 'tester']);

        // Initialize captcha service
        $captchaRepository = new CaptchaRepository();
        $captcha = new Captcha($captchaRepository);

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password',
            'captcha_code' => $captcha->generateForTest()
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create(['username' => 'tester']);

        // Initialize captcha service
        $captchaRepository = new CaptchaRepository();
        $captcha = new Captcha($captchaRepository);

        $this->post('/login', [
            'username' => $user->username,
            'password' => 'wrong-password',
            'captcha_code' => $captcha->generateForTest()
        ]);

        $this->assertGuest();
    }
}