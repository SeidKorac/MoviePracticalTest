<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    
    public function register(RegisterRequest $request)
    {
        $request->password = bcrypt($request->password);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        $token = $user->createToken('authToken')->accessToken;

        return response()->json(['user' => $user, 'access_token' => $token]);
    }

}
