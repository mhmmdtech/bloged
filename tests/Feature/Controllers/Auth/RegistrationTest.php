<?php

namespace Tests\Feature\Auth;

use App\Enums\GenderStatus;
use App\Enums\MilitaryStatus;
use App\Providers\RouteServiceProvider;
use App\Services\Captcha\Captcha;
use App\Services\Captcha\CaptchaRepository;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register_with_filling_required_information(): void
    {
        // Initialize captcha service
        $captchaRepository = new CaptchaRepository();
        $captcha = new Captcha($captchaRepository);

        $response = $this->post('/register', [
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
            'captcha_code' => $captcha->generateForTest()
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}