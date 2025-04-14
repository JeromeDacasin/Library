<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowedBookController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\Reports\UserHistoryController;
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
    
    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::apiResource('/departments', DepartmentController::class);
        Route::apiResource('/users', UserController::class);
        Route::apiResource('/roles', RoleController::class);
        Route::apiResource('/authors', AuthorController::class);
        Route::patch('/books/{id}/restore', [BookController::class, 'restore']);
        Route::apiResource('/books', BookController::class);
        Route::apiResource('/publishers', PublisherController::class);
        Route::apiResource('/penalties', PenaltyController::class);
        Route::apiResource('/request-books', BorrowedBookController::class);

        Route::get('/exports', [BorrowedBookController::class, 'exports']);
        Route::get('/my-books', [BorrowedBookController::class, 'myBooks']);
        Route::post('/logout',  [UserController::class, 'logout']);
        Route::put('user/password', [UserController::class, 'changePassword']);

        Route::group(['prefix' => '/histories'], function () {
            Route::get('/{id}', [UserHistoryController::class, 'userHistory']);
        });

    });
    Route::post('/login',  [UserController::class, 'login']);
});




