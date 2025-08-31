<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(Request $r): JsonResponse
    {
        $r->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $user = User::create([
            'name' => $r->name,
            'email' => $r->email,
            'date' => today('Asia/Kuala_Lumpur')->isoFormat('DD MMMM YYYY'),
            'password' => $r->password,
            'role' => 'intern',
            'photo' => '-'
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }
}
