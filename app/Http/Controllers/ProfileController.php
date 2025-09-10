<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function getProfile(): JsonResponse
    {
        $profile = Auth::user()->first(['name', 'email', 'date', 'instansi', 'periode', 'role', 'photo']);

        return response()->json([
            'status' => 'OK',
            'data' => $profile,
        ]);
    }

    public function profile(Request $r): JsonResponse
    {
        $profile = Auth::user();

        $r->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'photo' => ['mimes:png,jpg,webp', 'max:1024'],
        ]);

        if ($r->file('avatar')) {
            // delete the old file when is available at directory,
            if ($profile->photo) {
                Storage::disk('public')->delete("img/avt/$profile->photo");
            }

            // and store the new one.
            $fileName = Str::random(70);

            $r->file('avatar')->storeAs('img/avt', $fileName, 'public');
        }

        $profile->update([
            'name' => $r->name,
            'email' => $r->email,
            'date' => $profile->date,
            'instansi' => $profile->instansi,
            'periode' => $profile->periode,
            'password' => $profile->password,
            'role' => $profile->role,
        ]);

        if ($r->photo) {
            $profile->update(['photo' => $fileName]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $profile,
        ]);
    }
}
