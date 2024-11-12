<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_register_success()
    {
        $email = fake()->email();

        $response = $this->postJson($this->apiRoute('/auth/register'), [
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

    public function test_user_register_throw_duplication_error()
    {
        $email = fake()->email();

        $this->postJson($this->apiRoute('/auth/register'), [
            'email' => $email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response = $this->postJson($this->apiRoute('/auth/register'), [
            'email' => $email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The email has already been taken.'
            ]);
    }

    #TODO add tests for other user create request rules
}
