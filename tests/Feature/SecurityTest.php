<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_web_responses_include_security_headers(): void
    {
        $this->get('/login')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_registration_rejects_a_weak_password(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Example User',
            'email' => 'example@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect()->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'example@example.com']);
    }

    public function test_login_is_rate_limited(): void
    {
        User::factory()->create([
            'email' => 'example@example.com',
            'password' => Hash::make('CorrectPassword1'),
        ]);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('login.store'), [
                'email' => 'example@example.com',
                'password' => 'wrong-password',
            ])->assertRedirect()->assertSessionHasErrors('email');
        }

        $this->post(route('login.store'), [
            'email' => 'example@example.com',
            'password' => 'wrong-password',
        ])->assertStatus(429);
    }
}