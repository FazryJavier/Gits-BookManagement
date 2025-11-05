<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validate['name'],
            'username' => $validate['username'],
            'email' => $validate['email'],
            'password' => Hash::make($validate['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return $this->success([
            'user' => $user,
            'token' => $token,
        ], 'User registered successfully', 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->error('Invalid credentials', 401);
            }
        } catch (JWTException $e) {
            return $this->error('Could not create token', 500);
        }

        return $this->success(['token' => $token], 'Login successful');
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if ($token) {
            JWTAuth::setToken($token)->invalidate();
        }
        return $this->success(null, 'Successfully logged out');
    }
}
