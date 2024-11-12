<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_logout(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $loginResponse = $this->postJson($this->apiRoute('/auth/login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $loginResponse->json('token');

        $logoutResponse = $this->getJson($this->apiRoute('/auth/logout'), [
            'Authorization' => 'Bearer ' . $token
        ]);

        $logoutResponse->assertStatus(200)
            ->assertJson(['message' => 'Successfully logged out.']);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }
}
