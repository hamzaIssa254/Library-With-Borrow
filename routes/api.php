<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\UserController;

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



    /**
     * Auth Routes
     *
     * These routes handle user authentication, including login, registration, and logout.
    */
    Route::controller(AuthController::class)->group(function () {
        /**
         * Login Route
         *
         * @method POST
         * @route /v1/login
         * @desc Authenticates a user and returns a JWT token.
         */
        Route::post('login', 'login');

        /**
         * Register Route
         *
         * @method POST
         * @route /v1/register
         * @desc Registers a new user and returns a JWT token.
         */
        Route::post('register', 'register');

        /**
         * Logout Route
         *
         * @method POST
         * @route /v1/logout
         * @desc Logs out the authenticated user.
         * @middleware auth:api
         */
        Route::post('logout', 'logout')->middleware('auth:api');
    });

    /**
     * Author Management Routes
     *
     * These routes handle author management operations.
     */
    Route::apiResource('books', BookController::class)->middleware(['auth:api']);
    Route::post('borrows/{borrow}/return', [BorrowController::class, 'retrieveBook'])->middleware(['auth:api']);
    Route::apiResource('categories', CategoryController::class)->middleware(['auth:api']);
    Route::apiResource('borrows', BorrowController::class)->middleware(['auth:api']);
    Route::apiResource('rates', RateController::class)->middleware(['auth:api']);
    Route::apiResource('users',UserController::class)->middleware(['auth:api','admin']);



