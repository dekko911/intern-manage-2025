<?php

// Route::get||post||patch||delete

// Route::can(); dapat ide.

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

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['api', 'auth:sanctum', 'ability:admin'])->group(function () {
    //
});

Route::middleware(['api', 'auth:sanctum', 'ability:admin,staff'])->group(function () {
    //
});

Route::middleware(['api', 'auth:sanctum', 'ability:intern'])->group(function () {
    //
});

Route::apiResource('/users', UserController::class);
Route::apiResource('/intern_attends', InternAttendController::class);
Route::apiResource('/job_interns', JobInternController::class);
