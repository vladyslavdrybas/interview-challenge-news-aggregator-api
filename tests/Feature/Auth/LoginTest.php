<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_can_login_via_email_password(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->postJson($this->apiRoute('/auth/login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $response->json('token');

        # Make a request to a protected route with the token
        $response = $this->getJson($this->apiRoute(''), [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);
    }

    public function test_can_not_login_via_wrong_password(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->postJson($this->apiRoute('/auth/login'), [
            'email' => $user->email,
            'password' => 'password1',
        ]);

        $response->assertStatus(401);
    }

    public function test_can_not_login_via_short_password(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->postJson($this->apiRoute('/auth/login'), [
            'email' => $user->email,
            'password' => '1',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The password field must be at least 6 characters.'
            ]);
    }
}
