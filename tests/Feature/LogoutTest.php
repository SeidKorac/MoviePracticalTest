<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
        $this->seed();
    }

    /**
     * A successful logout.
     */
    public function test_successful_logout(): void
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

        $token = $response->json('access_token');
        assert($token !== null);

        $logoutResponse = $this->postJson('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $logoutResponse->assertStatus(200);
        $tokenAfterLogout = $logoutResponse->json('access_token');
        assert($tokenAfterLogout === null);
    }

    /**
     * A failed logout.
     */
    public function test_failed_logout(): void
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

        $token = $response->json('access_token');
        assert($token !== null);

        $logoutResponse = $this->postJson('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token . 'wrong',
        ]);

        $logoutResponse->assertStatus(401);
    }
}
