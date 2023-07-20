<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\MovieCollection;
use App\Http\Resources\MovieResource;

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
