<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoDController;
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
});

Route::middleware(['auth:sanctum', 'ability:admin'])->group(function () {
    Route::apiResource('intern_attends', InternAttendController::class);
    Route::apiResource('job_interns', JobInternController::class);
});

Route::middleware(['auth:sanctum', 'ability:admin,staff'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::get('/job_intern', [JobInternController::class, 'index']);
    Route::get('/intern_attend', [InternAttendController::class, 'index']);
    Route::post('/job_intern', [JobInternController::class, 'store']);
});

Route::middleware(['auth:sanctum', 'ability:intern'])->group(function () {
    Route::get('/attend_intern', [InternAttendController::class, 'index']);
    Route::post('/attend_intern', [InternAttendController::class, 'store']);
    Route::get('/intern_job', [JobInternController::class, 'index']);
    Route::post('/intern_job', [JobInternController::class, 'store']);
});

Route::apiResource('cods', CoDController::class);
