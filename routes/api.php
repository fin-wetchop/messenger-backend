<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\ChannelMemberController;
use App\Http\Controllers\ChannelMessageController;
use App\Http\Controllers\UserController;
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

Route::controller(AuthController::class)->prefix("auth")->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');

    Route::middleware('auth:sanctum')->group(
        function () {
            Route::post('logout', 'logout');
            Route::post('refresh', 'refresh');
        }
    );
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)
        ->prefix('users')
        ->group(
            function () {
                Route::get('', 'index');
                Route::get('{id}', 'show');
                Route::patch('{id}', 'update');
                Route::delete('{id}', 'destroy');
            }
        );

    Route::controller(ChannelController::class)
        ->prefix('channels')
        ->group(
            function () {
                Route::get('', 'index');
                Route::post('', 'store');
                Route::get('{id}', 'show');
                Route::delete('{id}', 'destroy');

                Route::prefix('{channel_id}')
                    ->group(
                        function () {
                            Route::controller(ChannelMemberController::class)
                                ->prefix('members')
                                ->group(
                                    function () {
                                        Route::get('', 'index');
                                        Route::post('', 'store');
                                        Route::delete('{id}', 'destroy');
                                    }
                                );

                            Route::controller(ChannelMessageController::class)
                                ->prefix('messages')
                                ->group(
                                    function () {
                                        Route::get('', 'index');
                                        Route::post('', 'store');
                                        Route::get('{id}', 'show');
                                        Route::patch('{id}', 'update');
                                        Route::delete('{id}', 'destroy');
                                    }
                                );
                        }
                    );
            }
        );
});
