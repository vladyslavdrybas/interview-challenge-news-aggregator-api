<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebHomeTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_web_homepage_redirect(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
