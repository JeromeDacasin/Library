<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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


Route::group(['prefix' => '/v1'], function() {
    
    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::apiResource('/departments', DepartmentController::class);
        Route::apiResource('/users', UserController::class);
        Route::apiResource('/roles', RoleController::class);
        Route::apiResource('/authors', AuthorController::class);
        Route::post('/logout',  [UserController::class, 'logout']);
    });
    Route::apiResource('/books', BookController::class);
    
    Route::post('/login',  [UserController::class, 'login']);
});




