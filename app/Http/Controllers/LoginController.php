<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('authToken')->accessToken;
            return response()->json(['user' => $user, 'access_token' => $token]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

}
