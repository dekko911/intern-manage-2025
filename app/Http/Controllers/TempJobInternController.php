<?php

namespace App\Http\Controllers;

use App\Models\TempJobIntern;
use Illuminate\Http\Request;

class TempJobInternController extends Controller
{
    public function index()
    {
        $temp_job_interns = TempJobIntern::latest('created_at')->with(['user', 'job_intern'])->where(function ($q) {
            $search = request('q');

            if ($search) {
                return $q->where('created', 'like', "%$search%")
                    ->orWhere('task', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhere('deadline', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('expired_at', 'like', "%$search%")
                    ->orWhereRelation('user', 'name', 'like', "%$search%");
            }
        })->get();

        return response()->json([
            'status' => 'OK',
            'data' => $temp_job_interns,
        ]);
    }
}
