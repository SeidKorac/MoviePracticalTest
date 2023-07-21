<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
        $this->seed();
    }
    
    /**
     * A test succeddful login.
     */
    public function test_successful_login(): void
    {
        $user = User::factory()->create([
            'name' => 'tester',
            'email' => 'test@tester.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@tester.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);

    }

    /**
     * A test failed login.
     */
    public function test_failed_login(): void
    {
        $user = User::factory()->create([
            'name' => 'tester',
            'email' => 'test@tester.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@tester.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }
}
