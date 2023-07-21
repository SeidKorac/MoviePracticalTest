<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use Laravel\Passport\Passport;
use App\Models\Movie;
use App\Models\MovieGenre;
use Illuminate\Support\Facades\Cache;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
        $this->seed();
    }

    /**
     * Get all movies.
     */
    public function test_movies_index(): void
    {
        $response = $this->getJson('/api/movies');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'synopsis',
                    'director',
                    'duration',
                    'releaseDate',
                    'genre' => [
                        '*' => [
                            'id',
                            'name'
                        ],
                    ],
                ]
            ]
        ]);

        $response->assertJsonCount(10, 'data');
    }

    /**
     * Get a movie.
     */
    public function test_movies_show(): void
    {
        $slug = 'test_movie';
        $response = $this->getJson('/api/movies/'.$slug);
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'synopsis',
                'director',
                'duration',
                'releaseDate',
                'genre' => [
                    '*' => [
                        'id',
                        'name'
                    ],
                ],
            ]
        ]);
    }

    /**
     * Get a movie with invalid slug.
     */
    public function test_movies_show_invalid_slug(): void
    {
        $slug = 'invalid_slug';
        $response = $this->getJson('/api/movies/'.$slug);
        $response->assertStatus(404);
    }

    /**
     * Successful movie creation.
     */
    public function test_movies_store(): void
    {
        Passport::actingAs(
            User::factory()->create(),
        );

        $response = $this->postJson('/api/movies', [
            'title' => 'test_movie',
            'synopsis' => 'test_movie',
            'director' => 'test_movie',
            'duration' => 120,
            'releaseDate' => '2020-01-01',
            'genre' => [1, 2],
        ]);

        $response->assertStatus(201);
    }

    /**
     * Fail movie creation.
     */
    public function test_movies_store_fail(): void
    {
        Passport::actingAs(
            User::factory()->create(),
        );

        $response = $this->postJson('/api/movies', [
            'title' => 'test_movie',
            'synopsis' => 'test_movie',
            'director' => 'test_movie',
            'duration' => 120,
            'releaseDate' => '2020-01-01',
            'genre' => [1, 2],
        ]);

        $response->assertStatus(201);

        $response = $this->postJson('/api/movies', [
            'title' => 'test_movie',
            'synopsis' => 'test_movie',
            'director' => 'test_movie',
            'duration' => 120,
            'releaseDate' => '2020-01-01',
            'genre' => [1, 2],
        ]);

        $response->assertStatus(400);
    }

    /**
     * Successful movie update.
     */
    public function test_movies_update(): void
    {
        Passport::actingAs(
            User::factory()->create(),
        );

        $response = $this->putJson('/api/movies/test_movie', [
            'title' => 'test_movie1',
            'synopsis' => 'test_movie',
            'director' => 'test_movie',
            'duration' => 120,
            'releaseDate' => '2020-01-01',
            'genre' => [1, 2],
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => 'test_movie1',
        ]);
    }

    /**
     * Delete movie.
     */
    public function test_movies_delete(): void
    {
        Passport::actingAs(
            User::factory()->create(),
        );

        $response = $this->deleteJson('/api/movies/test_movie');
        $response->assertStatus(204);
    }

    /**
     * Delete movie with invalid slug.
     */
    public function test_movies_delete_invalid_slug(): void
    {
        Passport::actingAs(
            User::factory()->create(),
        );

        $response = $this->deleteJson('/api/movies/invalid_slug');
        $response->assertStatus(404);
    }

}
