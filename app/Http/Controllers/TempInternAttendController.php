<?php

namespace App\Http\Controllers;

use App\Models\TempInternAttend;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TempInternAttendController extends Controller
{
    public function index(): JsonResponse
    {
        $search = request('q');

        switch (Auth::user()->role) {
            case 'admin':
            case 'staff':
                $temp_intern_attends = TempInternAttend::latest('created_at')->with(['user', 'intern_attend'])->when(
                    $search,
                    fn($q) =>
                    $q->where('status', 'like', "%$search%")
                        ->orWhere('tanggal', 'like', "%$search%")
                        ->orWhere('jam_masuk', 'like', "%$search%")
                        ->orWhere('jam_keluar', 'like', "%$search%")
                        ->orWhere('expired_at', 'like', "%$search%")
                        ->orWhereRelation('user', 'name', 'like', "%$search%")
                )->get();
                break;
            case 'intern':
                $temp_intern_attends = TempInternAttend::latest('created_at')->with(['user', 'intern_attend'])->when(
                    $search,
                    fn($q) =>
                    $q->where('status', 'like', "%$search%")
                        ->orWhere('tanggal', 'like', "%$search%")
                        ->orWhere('jam_masuk', 'like', "%$search%")
                        ->orWhere('jam_keluar', 'like', "%$search%")
                        ->orWhere('expired_at', 'like', "%$search%")
                        ->orWhereRelation('user', 'name', 'like', "%$search%")
                )->where('user_id', Auth::id())->get();
                break;
            default;
        }

        return response()->json([
            'status' => 'OK',
            'data' => $temp_intern_attends,
        ]);
    }
}
