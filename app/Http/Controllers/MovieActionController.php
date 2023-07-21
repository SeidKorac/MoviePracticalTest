<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\MovieCollection;
use App\Http\Resources\MovieResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MovieActionController extends Controller
{
    public function favorite(Movie $movie)
    {
        $user = Auth::user();

        try {
            $user->favoriteMovies()->attach($movie->id);

            $favoriteMovies = Cache::get('user_id_'.$user->id);
            if ($favoriteMovies) {
                $favoriteMovies->push($movie);
            } else {
                $favoriteMovies = collect([$movie]);
            }
            Cache::put('user_id_' . $user->id, $favoriteMovies);

        } catch (Exception $e) {
            return response()->json(['message' => 'Movie already favorited']);
        }

        return response()->json(['message' => 'Successfully favorited movie']);
    }

    public function unfavorite(Movie $movie)
    {
        $user = Auth::user();

        try {
            $user->favoriteMovies()->detach($movie->id);

            $favoriteMovies = Cache::get('user_id_'.$user->id);
            if ($favoriteMovies) {
                $favoriteMovies = $favoriteMovies->filter(function ($favoriteMovie) use ($movie) {
                    return $favoriteMovie->id !== $movie->id;
                });
                if($favoriteMovies->count() === 0) {
                    Cache::forget('user_id_' . $user->id);
                } else {
                    Cache::put('user_id_' . $user->id, $favoriteMovies);
                }
            }

        } catch (Exception $e) {
            return response()->json(['message' => 'Movie already unfavorited']);
        }

        return response()->json(['message' => 'Successfully unfavorited movie']);
    }

    public function followMovie(Movie $movie)
    {
        $user = Auth::user();

        try {
            $user->followedMovies()->attach($movie->id);
        } catch (Exception $e) {
            return response()->json(['message' => 'Movie already followed']);
        }

        return response()->json(['message' => 'Successfully followed movie']);
    }

    public function unfollowMovie(Movie $movie)
    {
        $user = Auth::user();

        try {
            $user->followedMovies()->detach($movie->id);
        } catch (Exception $e) {
            return response()->json(['message' => 'Movie already unfollowed']);
        }

        return response()->json(['message' => 'Successfully unfollowed movie']);
    }
}
