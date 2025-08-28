<?php

namespace App\Http\Controllers;

use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// This Was Called Cast (change the first variable type to actual variable type), yeah i think that's definition.
// $numberString = "1234567890";
// $numberToInt = (int) $numberString;
// echo gettype($numberToInt);

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        return $this->userService->getDataUser()->toJson();
    }

    public function show($id): JsonResponse
    {
        return $this->userService->getUserById($id)->toJson();
    }

    public function store(): JsonResponse
    {
        return $this->userService->createUser()->toJson();
    }

    public function update($id): JsonResponse
    {
        return $this->userService->updateUser($id)->toJson();
    }

    public function destroy($id): JsonResponse
    {
        return $this->userService->deleteUserById($id)->toJson();
    }
}
