<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // this for authenticate when user trying to login.
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = User::where('email', $request->email)->first();

            $token = $user->createToken($user->name, [$user->role], now()->addDay());

            return response()->json([
                'status' => 'success',
                'token' => $token,
                'user' => $user,
            ]);
        }

        // if user has not match with their crendentials, return this.
        return response()->json([
            'status' => 'fail',
            'message' => 'Wrong Email or Password !',
        ]);
    }
}
