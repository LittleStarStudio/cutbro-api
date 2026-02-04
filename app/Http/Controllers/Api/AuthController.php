<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required|min:6"
        ]);

        if (!Auth::attempt($request->only('email','password'))) {
            return $this->error('Invalid credentials', 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api')->plainTextToken;

        return $this->success([
            'token' => $token,
            'role' => $user->role,
            'user' => $user,
        ]);
    }

    public function me(Request $request)
    {
        if (!$request->user()) {
            return $this->error('Unauthenticated', 401);
        }

        return $this->success($request->user());
    }

    public function logout(Request $request)
    {
        if (!$request->user()) {
            return $this->error('Unauthenticated', 401);
        }

        $request->user()->currentAccessToken()->delete();
        return $this->success('Logged Out');
    }
}
