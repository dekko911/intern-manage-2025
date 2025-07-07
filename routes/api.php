<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InternAttendController;
use App\Http\Controllers\JobInternController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get(
    '/user',
    fn(Request $request) =>
    $request->user()
)->middleware('auth:sanctum');

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [UserController::class, 'store'])->name('register');
    Route::apiResource('/users', UserController::class);
    Route::apiResource('/intern_attends', InternAttendController::class);
    Route::apiResource('/job_interns', JobInternController::class);
});

Route::middleware(['auth:sanctum', 'ability:admin'])->group(function () {
    //
});

Route::middleware(['auth:sanctum', 'ability:admin,staff'])->group(function () {
    //
});

Route::middleware(['auth:sanctum', 'ability:intern'])->group(function () {
    //
});
