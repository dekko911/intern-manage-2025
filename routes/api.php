<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoDController;
use App\Http\Controllers\InternAttendController;
use App\Http\Controllers\JobInternController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TempInternAttendController;
use App\Http\Controllers\TempJobInternController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get(
    '/user',
    fn(Request $request) =>
    $request->user()
)->middleware(['auth:sanctum', 'throttle:api']);

Route::middleware(['guest', 'throttle:api'])->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
});

Route::middleware(['auth:sanctum', 'ability:admin', 'throttle:api'])->group(function () {
    Route::apiResource('intern_attends', InternAttendController::class);
    Route::apiResource('job_interns', JobInternController::class);
});

Route::middleware(['auth:sanctum', 'ability:admin,staff', 'throttle:api'])->group(function () {
    Route::post('/profile', [ProfileController::class, 'profile']);
    Route::apiResource('tmp_ias', TempInternAttendController::class);
    Route::apiResource('tmp_jis', TempJobInternController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('search', SearchController::class);
    Route::apiResource('cods', CoDController::class);
    Route::get('/job_intern', [JobInternController::class, 'index']);
    Route::post('/job_intern', [JobInternController::class, 'store']);
    Route::get('/intern_attend', [InternAttendController::class, 'index']);
});

Route::middleware(['auth:sanctum', 'ability:intern', 'throttle:api'])->group(function () {
    Route::post('/profile', [ProfileController::class, 'profile']);
    Route::apiResource('search', SearchController::class);
    Route::apiResource('tmp_ia', TempInternAttendController::class);
    Route::apiResource('tmp_ji', TempJobInternController::class);
    Route::post('/attend_intern', [InternAttendController::class, 'store']);
    Route::patch('/attend_intern/{id}', [InternAttendController::class, 'update']);
    Route::get('/cod', [CoDController::class, 'index']);
    Route::get('/generate-pdf', [PDFController::class, 'generatePDFByUserId']);
});
