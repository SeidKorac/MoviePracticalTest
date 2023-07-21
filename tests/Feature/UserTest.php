<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use Laravel\Passport\Passport;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
        $this->seed();
    }
    /**
     * get the logged in user.
     */
    public function test_successful_get_user(): void
    {
        Passport::actingAs(
            User::factory()->create(),
        );

        $response = $this->getJson('/api/user');
        $response->assertStatus(200);
    }

    /**
     * fail getting the logged in user
     */
    public function test_failed_get_user(): void
    {
        $response = $this->getJson('/api/user');
        $response->assertStatus(401);
    }
}
