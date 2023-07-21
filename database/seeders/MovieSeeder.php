<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\MovieGenre;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Movie::factory()->create([
            'title' => 'Test Movie',
        ]);
        
        Movie::factory(9)->create();

        Movie::all()->each(function ($movie) {
            $genres = MovieGenre::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $movie->genre()->attach($genres);
        });       
    }
}
