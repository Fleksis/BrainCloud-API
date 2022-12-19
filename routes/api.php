<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\FolderController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\TopicController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('user/image/{user}', [UserController::class, 'getFile'])->name('user.image');

Route::get('/download/{user}', [AuthController::class, 'download']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'user']);
    Route::apiResource('users', UserController::class);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::apiResource('folders', FolderController::class);
    Route::get('/user_folders', [FolderController::class, 'getUserFolders']);
    Route::apiResource('files', FileController::class);
    Route::apiResource('supports', SupportController::class);
    Route::apiResource('topics', TopicController::class);
});
