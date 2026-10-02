<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Actions\Auth\AuthenticateUser;
use App\Http\Requests\Auth\{LoginRequest,RegisterRequest};
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        $token = $user->createToken($request->name);

        // TODO: login right away or wait for email confirmation

        return [
            'message' => 'User registered successfully.'
        ];
    }

    
    public function login(LoginRequest $request, AuthenticateUser $authenticateUser)
    {
        $authenticateUser->handle($request);

        $user = auth()->user();

        $token = $user->createToken($user->name)->plainTextToken;

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name 
            ],
            'token' => $token
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return [
            'message' => 'You are logged out'
        ];
    }
}
