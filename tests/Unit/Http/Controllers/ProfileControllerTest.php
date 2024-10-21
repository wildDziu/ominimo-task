<?php

namespace Tests\Unit\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_returns_correct_view()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Profile');
    }

    public function test_update_user_profile()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->patch(route('profile.update'), $updatedData);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect(route('profile.edit'));
    }

    public function test_user_email_verification_status_is_unchanged_when_email_address_is_unchanged()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->patch(route('profile.update'), [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

        $response->assertRedirect(route('profile.edit'));
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_user_email_verification_status_is_unset_when_email_address_is_changed()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->patch(route('profile.update'), [
            'name' => 'Test User',
            'email' => 'newemail@example.com',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_destroy_user_account()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $this->actingAs($user);

        $response = $this->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

        $this->assertModelMissing($user);
        $response->assertRedirect('/');
    }

    public function test_correct_password_must_be_provided_to_destroy_account()
    {
        $user = User::factory()->create([
            'password' => Hash::make('correct_password'),
        ]);
        $this->actingAs($user);

        $response = $this->delete(route('profile.destroy'), [
            'password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertModelExists($user);
    }
}
