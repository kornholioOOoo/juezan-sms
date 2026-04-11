<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\ScholarshipController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->prefix('admin')->group(function () {


    Route::post('/logout', [AuthController::class, 'logout']);


    Route::get('/student', [StudentController::class, 'index']);  
    Route::post('/student', [StudentController::class, 'store']); 
    Route::get('/student/{id}', [StudentController::class, 'show']);  
    Route::put('/student/{id}', [StudentController::class, 'update']); 
    Route::delete('/student/{id}', [StudentController::class, 'destroy']);


    Route::get('/scholarships', [ScholarshipController::class, 'index']); 
    Route::post('/scholarships', [ScholarshipController::class, 'store']); 
    Route::get('/scholarships/{id}', [ScholarshipController::class, 'show']); 
    Route::put('/scholarships/{id}', [ScholarshipController::class, 'update']); 
    Route::delete('/scholarships/{id}', [ScholarshipController::class, 'destroy']);


    Route::get('/applications', [ApplicationController::class, 'index']); 
    Route::get('/applications/{id}', [ApplicationController::class, 'show']); 
    Route::put('/applications/{id}/approve', [ApplicationController::class, 'approve']); 
    Route::put('/applications/{id}/reject', [ApplicationController::class, 'reject']); 
});


Route::middleware('auth:sanctum')->prefix('users')->group(function () {

   
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::get('/scholarships', [ScholarshipController::class, 'index']);
    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::get('/applications/{id}', [ApplicationController::class, 'show']);
    Route::put('/applications/{id}', [ApplicationController::class, 'update']);
    Route::delete('/applications/{id}', [ApplicationController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    return $request->user();
});

