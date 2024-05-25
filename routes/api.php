<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response(status: Response::HTTP_FORBIDDEN));

Route::post('/auth', 'AuthController');

Route::prefix('users')->group(function () {
    Route::post('/', 'UserStoreController')->name('users.store');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/money/send', 'MoneySendController')->name('money.send');
});
