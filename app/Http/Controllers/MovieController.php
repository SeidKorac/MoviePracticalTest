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

class MovieController extends Controller
{
    
    public function index()
    {
        try {
            return MovieResource::collection(Movie::paginate());
        } catch (Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function showFavorites()
    {
        try {
            $user = Auth::user();
            $favoriteMovies = Cache::get('user_id_'.$user->id);

            if (!$favoriteMovies) {
                $favoriteMovies = $user->favoriteMovies()->get();
                dd($favoriteMovies);
                Cache::put('user_id_' . $user->id, $favoriteMovies);
            }

            return MovieResource::collection($favoriteMovies);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function showFollowedMovies()
    {
        try {
            $user = Auth::user();
            return MovieResource::collection($user->followedMovies()->paginate()->get());
        } catch (Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function store(Request $request)
    {
        $validation = $request->validate([
            'title' => 'required|string',
            'synopsis' => 'required|string',
            'director' => 'required|string',
            'duration' => 'integer',
            'releaseDate' => 'date',
        ]);

        try {
            $movie = Movie::create($request->all());
            return new MovieResource($movie);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

}
