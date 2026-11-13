<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'fakultas' => 'FMIPA',
                'program_studi' => 'Informatika',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
                'fakultas' => $user->fakultas ?? 'FMIPA',
                'program_studi' => $user->program_studi ?? 'Informatika',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    /**
     * Buat file gambar valid tanpa memerlukan ekstensi PHP GD.
     */
    private function createFakeImage(string $filename = 'avatar.png'): UploadedFile
    {
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        return UploadedFile::fake()->createWithContent($filename, $png);
    }

    public function test_user_can_upload_profile_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = $this->createFakeImage('avatar.png');

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'fakultas' => $user->fakultas ?? 'FMIPA',
                'program_studi' => $user->program_studi ?? 'Informatika',
                'foto_profil' => $file,
            ]);

        $response->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertNotNull($user->foto_profil);
        Storage::disk('public')->assertExists($user->foto_profil);
    }

    public function test_old_photo_is_deleted_when_new_photo_uploaded(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        // Upload first photo
        $firstFile = $this->createFakeImage('first.png');
        $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'fakultas' => $user->fakultas ?? 'FMIPA',
            'program_studi' => $user->program_studi ?? 'Informatika',
            'foto_profil' => $firstFile,
        ]);

        $user->refresh();
        $firstPath = $user->foto_profil;
        $this->assertNotNull($firstPath);
        Storage::disk('public')->assertExists($firstPath);

        // Upload second photo
        $secondFile = $this->createFakeImage('second.png');
        $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'fakultas' => $user->fakultas ?? 'FMIPA',
            'program_studi' => $user->program_studi ?? 'Informatika',
            'foto_profil' => $secondFile,
        ]);

        $user->refresh();
        $secondPath = $user->foto_profil;

        // Old file should be deleted, new file should exist
        Storage::disk('public')->assertMissing($firstPath);
        Storage::disk('public')->assertExists($secondPath);
    }

    public function test_user_can_delete_profile_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        // Upload a photo first
        $file = $this->createFakeImage('avatar.png');
        $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'fakultas' => $user->fakultas ?? 'FMIPA',
            'program_studi' => $user->program_studi ?? 'Informatika',
            'foto_profil' => $file,
        ]);

        $user->refresh();
        $photoPath = $user->foto_profil;
        $this->assertNotNull($photoPath);

        // Delete the photo
        $response = $this
            ->actingAs($user)
            ->delete('/profile/foto');

        $response->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertNull($user->foto_profil);
        Storage::disk('public')->assertMissing($photoPath);
    }

    public function test_invalid_file_type_is_rejected(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'fakultas' => $user->fakultas ?? 'FMIPA',
                'program_studi' => $user->program_studi ?? 'Informatika',
                'foto_profil' => $file,
            ]);

        $response->assertSessionHasErrors('foto_profil');
    }

    public function test_file_too_large_is_rejected(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        // Create a file larger than 2MB (2048 KB)
        $file = UploadedFile::fake()->create('big.jpg', 3000, 'image/jpeg');

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'fakultas' => $user->fakultas ?? 'FMIPA',
                'program_studi' => $user->program_studi ?? 'Informatika',
                'foto_profil' => $file,
            ]);

        $response->assertSessionHasErrors('foto_profil');
    }

    public function test_user_cannot_update_other_users_profile(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create([
            'name' => 'User B Original',
            'email' => 'userb@example.com',
        ]);

        // User A updates /profile (always updates their own profile, not user B's)
        $this->actingAs($userA)->patch('/profile', [
            'name' => 'Hacked Name',
            'email' => 'hacked@example.com',
            'fakultas' => $userA->fakultas ?? 'FMIPA',
            'program_studi' => $userA->program_studi ?? 'Informatika',
        ]);

        // User B's data should remain unchanged
        $userB->refresh();
        $this->assertSame('User B Original', $userB->name);
        $this->assertSame('userb@example.com', $userB->email);
    }

    public function test_user_can_change_password_with_correct_old_password(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'password',
                'password' => 'new-password123',
                'password_confirmation' => 'new-password123',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');
    }

    public function test_wrong_old_password_is_rejected(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'wrong-password',
                'password' => 'new-password123',
                'password_confirmation' => 'new-password123',
            ]);

        $response->assertSessionHasErrorsIn('updatePassword', 'current_password');
    }

    public function test_user_can_update_nim(): void
    {
        $user = User::factory()->create(['nim' => '2410111111']);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Updated Name',
                'email' => $user->email,
                'nim' => '2410999999',
                'fakultas' => 'FMIPA',
                'program_studi' => 'Informatika',
            ]);

        $response->assertSessionHasNoErrors();
        $user->refresh();

        $this->assertSame('2410999999', $user->nim);
    }

    public function test_user_cannot_update_nim_to_existing_duplicate_nim(): void
    {
        $userA = User::factory()->create(['nim' => '2410111111']);
        $userB = User::factory()->create(['nim' => '2410222222']);

        $response = $this
            ->actingAs($userA)
            ->patch('/profile', [
                'name' => $userA->name,
                'email' => $userA->email,
                'nim' => '2410222222',
                'fakultas' => 'FMIPA',
                'program_studi' => 'Informatika',
            ]);

        $response->assertSessionHasErrors('nim');
        $userA->refresh();
        $this->assertSame('2410111111', $userA->nim);
    }

    public function test_user_can_keep_their_own_nim_when_updating_profile(): void
    {
        $user = User::factory()->create(['nim' => '2410111111']);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Name Changed',
                'email' => $user->email,
                'nim' => '2410111111',
                'fakultas' => 'FMIPA',
                'program_studi' => 'Informatika',
            ]);

        $response->assertSessionHasNoErrors();
        $user->refresh();
        $this->assertSame('2410111111', $user->nim);
    }
}
