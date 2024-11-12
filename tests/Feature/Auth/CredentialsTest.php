<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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
            $this->apiRoute('/auth/password-reset'),
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

    public function test_user_can_reset_password(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $token = Sanctum::actingAs($user);

        $response = $this->postJson(
            $this->apiRoute('/auth/password-reset'),
            [
                'password_current' => 'password',
                'password' => 'newpassword',
            ],
            [
                'Authorization' => 'Bearer ' . $token
            ]
        );

        $response->assertStatus(200)
            ->assertJson(['message' => 'Password reset successfully.'])
        ;

        $newToken = $response->json('token');

        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password), 'Password is not changed.');
        $this->assertNotEquals($token, $newToken);
    }
}
