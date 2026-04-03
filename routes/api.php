<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\ScholarshipController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Authentication for both roles
Route::post('/register', [AuthController::class, 'register']); // for applicants
Route::post('/login', [AuthController::class, 'login']);       // for both roles

/*
|--------------------------------------------------------------------------
| Admin Routes (protected)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {

    // Admin logout & profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Applicant Management
    Route::get('/student', [StudentController::class, 'index']);  
    Route::post('/student', [StudentController::class, 'store']); 
    Route::get('/student/{id}', [StudentController::class, 'show']);  
    Route::put('/student/{id}', [StudentController::class, 'update']); 
    Route::delete('/student/{id}', [StudentController::class, 'destroy']);

    // Scholarship Management
    Route::get('/scholarships', [ScholarshipController::class, 'index']); 
    Route::post('/scholarships', [ScholarshipController::class, 'store']); 
    Route::get('/scholarships/{id}', [ScholarshipController::class, 'show']); 
    Route::put('/scholarships/{id}', [ScholarshipController::class, 'update']); 
    Route::delete('/scholarships/{id}', [ScholarshipController::class, 'destroy']);

    // Application Management
    Route::get('/applications', [ApplicationController::class, 'index']); 
    Route::get('/applications/{id}', [ApplicationController::class, 'show']); 
    Route::put('/applications/{id}/approve', [ApplicationController::class, 'approve']); 
    Route::put('/applications/{id}/reject', [ApplicationController::class, 'reject']); 
});

/*
|--------------------------------------------------------------------------
| Applicant/User Routes (protected)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->prefix('users')->group(function () {

    // Applicant logout & profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Scholarship Application
    Route::get('/scholarships', [ScholarshipController::class, 'index']); // view available scholarships
    Route::post('/applications', [ApplicationController::class, 'store']); // apply
    Route::get('/applications', [ApplicationController::class, 'index']); // view own applications
    Route::get('/applications/{id}', [ApplicationController::class, 'show']); // view single application
    Route::put('/applications/{id}', [ApplicationController::class, 'update']); // edit application
    Route::delete('/applications/{id}', [ApplicationController::class, 'destroy']); // delete application
});

/*
|--------------------------------------------------------------------------
| Default User Route
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    return $request->user();
});

