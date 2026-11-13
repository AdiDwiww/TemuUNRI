<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'nim' => '2410123456',
            'fakultas' => 'FMIPA',
            'program_studi' => 'Informatika',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_registration_fails_without_nim(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'fakultas' => 'FMIPA',
            'program_studi' => 'Informatika',
        ]);

        $response->assertSessionHasErrors('nim');
        $this->assertGuest();
    }

    public function test_registration_fails_without_fakultas(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'nim' => '2410123457',
            'program_studi' => 'Informatika',
        ]);

        $response->assertSessionHasErrors('fakultas');
        $this->assertGuest();
    }

    public function test_registration_fails_without_program_studi(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'nim' => '2410123458',
            'fakultas' => 'FMIPA',
        ]);

        $response->assertSessionHasErrors('program_studi');
        $this->assertGuest();
    }

    public function test_nim_must_be_unique(): void
    {
        // Create a user with a specific NIM
        User::factory()->create(['nim' => '2410999999']);

        // Attempt to register with same NIM
        $response = $this->post('/register', [
            'name' => 'Another User',
            'email' => 'another@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'nim' => '2410999999',
            'fakultas' => 'FMIPA',
            'program_studi' => 'Informatika',
        ]);

        $response->assertSessionHasErrors('nim');
        $this->assertGuest();
    }

    public function test_email_must_be_unique(): void
    {
        // Create a user with a specific email
        User::factory()->create(['email' => 'taken@example.com']);

        // Attempt to register with same email
        $response = $this->post('/register', [
            'name' => 'Another User',
            'email' => 'taken@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'nim' => '2410777777',
            'fakultas' => 'FMIPA',
            'program_studi' => 'Informatika',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
