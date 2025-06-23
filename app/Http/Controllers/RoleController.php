<?php

namespace App\Http\Controllers;

use App\Services\Role\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(): JsonResponse
    {
        return $this->roleService->getDataRole()->toJson();
    }

    public function show($id): JsonResponse
    {
        return $this->roleService->getRoleById($id)->toJson();
    }

    public function store(): JsonResponse
    {
        return $this->roleService->createRole()->toJson();
    }

    public function destroy($id): JsonResponse
    {
        return $this->roleService->deleteRoleById($id)->toJson();
    }
}
