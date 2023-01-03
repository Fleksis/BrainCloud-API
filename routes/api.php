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
Route::get('file/file/{file}', [FileController::class, 'getFile'])->name('file.file');

Route::middleware('auth:api')->group(function () {
    Route::apiResource('users', UserController::class)->only(['store', 'update']);
    Route::get('/me', [AuthController::class, 'user']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/user_filter', [UserController::class, 'userFilter']);
    Route::group(['middleware' => ['can:index.users']], function () {
        Route::resource('users', UserController::class)->only('index');
    });
    Route::group(['middleware' => ['can:delete.users']], function () {
        Route::resource('users', UserController::class)->only('delete');
    });

    Route::apiResource('folders', FolderController::class);
    Route::get('/user_folders', [FolderController::class, 'getUserFolders']);

    Route::apiResource('files', FileController::class);
    Route::post('/file_filter', [FileController::class, 'fileFilter']);
    Route::get('/user_files/{folder}', [FileController::class, 'getUserFiles']);

    Route::group(['middleware' => ['can:supports']], function () {
        Route::resource('supports', SupportController::class)->only('index');
    });

    Route::group(['middleware' => ['can:topics']], function () {
        Route::resource('topics', TopicController::class);
    });
});
