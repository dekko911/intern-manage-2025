<?php

namespace App\Http\Controllers;

use App\Models\TempJobIntern;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TempJobInternController extends Controller
{
    public function index(): JsonResponse
    {
        $search = request('q');

        $temp_job_interns = TempJobIntern::latest('created')
            ->with(['user', 'job_intern'])
            ->when(
                $search,
                fn($q) =>
                $q->where('created', 'like', "%$search%")
                    ->orWhere('task', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhere('deadline', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('expired_at', 'like', "%$search%")
                    ->orWhereRelation('user', 'name', 'like', "%$search%")
            )->get();

        return response()->json([
            'status' => 'OK',
            'data' => $temp_job_interns,
        ]);
    }
}
