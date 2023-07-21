<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
        $this->seed();
    }

    /**
     * A successful registration.
     */
    public function test_successful_registration(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'tester',
            'email' => 'test@tester.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }

    /**
     * A failed registration.
     */
    public function test_failed_registration(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'tester',
            'email' => 'test@tester.com',
            'password' => 'pasword',
        ]);

        $response->assertStatus(400);
    }
}
