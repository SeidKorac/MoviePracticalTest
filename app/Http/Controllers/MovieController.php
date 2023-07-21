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
use App\Http\Requests\CreateMovieRequest;
use App\Http\Requests\UpdateMovieRequest;

class MovieController extends Controller
{
    
    public function index()
    {
        try {
            return MovieResource::collection(Movie::all()->filter()->paginate()->get());
        } catch (Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function show(Movie $movie)
    {
        try {
            return new MovieResource($movie);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function showFavorites()
    {
        try {
            $user = Auth::user();
            $favoriteMovies = Cache::get('user_id_'.$user->id)->filter();

            if (!$favoriteMovies) {
                $favoriteMovies = $user->favoriteMovies()->filter();
                Cache::put('user_id_' . $user->id, $favoriteMovies->get());
            }

            return MovieResource::collection($favoriteMovies->paginate()->get());
        } catch (Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function showFollowedMovies()
    {
        try {
            $user = Auth::user();
            return MovieResource::collection($user->followedMovies()->filter()->paginate()->get());
        } catch (Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function store(CreateMovieRequest $request)
    {
        try {
            $movie = new Movie([
                'title' => $request->title,
                'synopsis' => $request->synopsis,
                'director' => $request->director,
                'duration' => $request->duration,
                'releaseDate' => $request->releaseDate,
            ]);
            $movie->movieGenres()->attach($request->movieGenres);

            $movie = Movie::create($request->all());
            return new MovieResource($movie);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        try {
            $movie->update([
                'title' => $request->title,
                'synopsis' => $request->synopsis,
                'director' => $request->director,
                'duration' => $request->duration,
                'releaseDate' => $request->releaseDate,
            ]);
            $movie->movieGenres()->sync($request->movieGenres);

            return new MovieResource($movie);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function destroy(Movie $movie)
    {
        try {
            $movie->delete();
            return response()->json($movie, 204);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

}
