<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Exception;

class MovieActionController extends Controller
{
    public function favorite(Movie $movie)
    {
        $user = Auth::user();

        try {
            $user->favoriteMovies()->attach($movie->id);
        } catch (Exception $e) {
            return response()->json(['message' => 'Movie already favorited']);
        }

        return response()->json(['message' => 'Successfully favorited movie']);
    }
}
