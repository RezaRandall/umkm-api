<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UmkmController;
use App\Http\Controllers\Api\UmkmControllerIndex;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Umkm Routes
Route::middleware('auth:sanctum')->group(function () {
   Route::apiResource('/umkms', UmkmController::class);
});

// Auth Routes
Route::get('/login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'getUser']);

// UMKM
Route::get('/index', [UmkmControllerIndex::class, 'index']);
Route::get('/detail/{id}', [UmkmControllerIndex::class, 'show']);