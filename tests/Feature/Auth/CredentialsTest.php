<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CredentialsTest extends TestCase
{
    use RefreshDatabase;

    public function test_force_user_to_set_new_password(): void
    {
        $user = User::factory()->create(['password' => 'password']);
        $token = Sanctum::actingAs($user);

        $response = $this->postJson(
            $this->apiRoute('/auth/password-update'),
            [
                'password_current' => 'password',
                'password' => 'password',
            ],
            [
                'Authorization' => 'Bearer ' . $token
            ]
        );

        $response->assertStatus(422)
            ->assertJson(['message' => 'Please, do not repeat yourself. It will make your account more secure.']);
    }

    public function test_user_can_update_password(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $token = Sanctum::actingAs($user);

        $response = $this->postJson(
            $this->apiRoute('/auth/password-update'),
            [
                'password_current' => 'password',
                'password' => 'newpassword',
            ],
            [
                'Authorization' => 'Bearer ' . $token
            ]
        );

        $response->assertStatus(200)
            ->assertJson(['message' => 'Password update successfully.'])
        ;

        $newToken = $response->json('token');

        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password), 'Password is not changed.');
        $this->assertNotEquals($token, $newToken);
    }

    public function test_user_can_request_password_reset_link(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Mock the sending of email
        Notification::fake();

        // Send request for password reset link
        $response = $this->postJson(
            $this->apiRoute('/auth/password-forgot'),
            [
                'email' => $user->email,
            ]
        );

        // Assert that the response is successful
        $response->assertStatus(200)
            ->assertJson(['message' => 'Reset link sent successfully.']);

        // Assert that the reset email was sent
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_user_cannot_request_password_reset_link_for_non_existent_user(): void
    {
        // Send request for password reset link for a non-existent user
        $response = $this->postJson(
            $this->apiRoute('/auth/password-forgot'),
            [
                'email' => 'nonexistentuser@example.com',
            ]
        );

        // Assert that the response is an error
        $response->assertStatus(422)
            ->assertJson(['message' => 'Not found.']);
    }
}
