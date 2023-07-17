<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    
public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (auth()->attempt($credentials)) {
        $user = auth()->user();
        $token = $user->createToken('authToken')->accessToken;
        return response()->json(['user' => $user, 'access_token' => $token]);
    } else {
        return response()->json(['error' => 'Unauthorised'], 401);
    }

}

}
