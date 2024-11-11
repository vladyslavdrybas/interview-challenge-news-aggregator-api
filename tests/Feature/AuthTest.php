<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_register_a_user()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'User registered successfully!'
            ]);

        // Assert that the user is stored in the database
        $this->assertDatabaseHas('users', [
            'email' => 'user@example.com',
        ]);
    }
}
