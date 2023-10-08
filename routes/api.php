<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\TransferController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'users']);

    Route::get('/user/balance/details', [UserController::class, 'user_balance_details']);
    Route::get('/user/income/details', [UserController::class, 'get_user_income_details']);
    Route::get('/user/outcome/details', [UserController::class, 'get_user_outcome_details']);

    Route::post('/transfer', [TransferController::class, 'transfer']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
