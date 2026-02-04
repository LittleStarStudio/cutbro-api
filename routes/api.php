<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::prefix('auth')->group(function () {

    Route::post('/login',[AuthController::class,'login'])
        ->middleware('throttle:5,1');

    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/me',[AuthController::class,'me']);
        Route::post('/logout',[AuthController::class,'logout']);
    });
});