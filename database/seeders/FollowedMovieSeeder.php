<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Movie;

class FollowedMovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        Movie::all()->each(function ($movie) use ($users) {
            $users->each(function ($user) use ($movie) {
                if (rand(0, 1)) {
                    $user->followedMovies()->attach($movie->id);
                }
            });
        });
    }
}
