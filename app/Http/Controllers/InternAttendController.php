<?php

namespace App\Http\Controllers;

use App\Services\InternAttend\InternAttendService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InternAttendController extends Controller
{
    protected $internAttendService;

    public function __construct(InternAttendService $internAttendService)
    {
        $this->internAttendService = $internAttendService;
    }

    public function index(): JsonResponse
    {
        return $this->internAttendService->getDataAttend()->toJson();
    }

    public function show($id): JsonResponse
    {
        return $this->internAttendService->getInternAttendById($id)->toJson();
    }

    public function store(): JsonResponse
    {
        return $this->internAttendService->createAttend()->toJson();
    }

    public function update(): JsonResponse
    {
        return $this->internAttendService->updateAttend()->toJson();
    }

    public function destroy($id): JsonResponse
    {
        return $this->internAttendService->deleteInternAttendById($id)->toJson();
    }
}