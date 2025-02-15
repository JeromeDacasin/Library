<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowedBookController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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
    
    Route::apiResource('/users', UserController::class);
    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::apiResource('/departments', DepartmentController::class);
        Route::apiResource('/roles', RoleController::class);
        Route::apiResource('/authors', AuthorController::class);
        Route::apiResource('/books', BookController::class);
        Route::apiResource('/publishers', PublisherController::class);
        Route::apiResource('/penalties', PenaltyController::class);
        Route::apiResource('/request-books', BorrowedBookController::class);
        Route::get('/my-books', [BorrowedBookController::class, 'myBooks']);
        Route::post('/logout',  [UserController::class, 'logout']);
        Route::put('user/password', [UserController::class, 'changePassword']);

    });
    Route::post('/login',  [UserController::class, 'login']);
});




