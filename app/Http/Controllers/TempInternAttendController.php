<?php

namespace App\Http\Controllers;

use App\Models\TempInternAttend;
use Illuminate\Http\Request;

class TempInternAttendController extends Controller
{
    public function index()
    {
        $temp_intern_attends = TempInternAttend::latest('created_at')->with(['user', 'intern_attend'])->where(function ($q) {
            $search = request('q');

            if ($search) {
                return $q->where('status', 'like', "%$search%")
                    ->orWhere('tanggal', 'like', "%$search%")
                    ->orWhere('jam_masuk', 'like', "%$search%")
                    ->orWhere('jam_keluar', 'like', "%$search%")
                    ->orWhereRelation('user', 'name', 'like', "%$search%");
            }
        })->get();

        return response()->json([
            'status' => 'OK',
            'data' => $temp_intern_attends,
        ]);
    }
}
