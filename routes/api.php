<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\TransferController;
use App\Http\Controllers\Api\UserController;
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
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/balance', [TransactionController::class, 'user_balance_details']);
    Route::get('/user/income/details', [TransactionController::class, 'get_user_income_details']);
    Route::get('/user/outcome/details', [TransactionController::class, 'get_user_outcome_details']);
    Route::get('/user/transactions', [TransactionController::class, 'get_user_transactions']);

    Route::post('/transfer', [TransferController::class, 'transfer']);

    Route::get('/user', [UserController::class, 'users']);
    Route::get('/user/profile', [UserController::class, 'profile_details']);
    Route::patch('/user/profile/edit', [UserController::class, 'edit_profile']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
