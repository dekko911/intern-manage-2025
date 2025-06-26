<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function login(): JsonResponse
    {
        $credentials = $this->request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // this for authenticate when user trying to login.
        if (Auth::attempt($credentials, $this->request->boolean('remember'))) {
            $user = User::where('email', $this->request->email)->first();

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

    // this function use for testing in postman for reason to make sure the token is deleted in table P_A_T.
    public function logout(): JsonResponse
    {
        // this need Bearer token in headers to logout.
        $this->request->user()->tokens()->delete();

        return response()->json([
            'status' => 'Goodbye !',
        ]);
    }
}
