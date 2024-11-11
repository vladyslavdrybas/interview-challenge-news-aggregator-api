<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_register_a_user()
    {
        $email = fake()->email();

        $response = $this->postJson('/api/v1/auth/register', [
            'email' => $email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'User registered successfully!'
            ]);

        // Assert that the user is stored in the database
        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);
    }
}
