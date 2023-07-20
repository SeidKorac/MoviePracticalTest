<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    
    public function show()
    {
        return response()->json(auth()->user());
    }

}
