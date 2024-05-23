<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response(status: Response::HTTP_FORBIDDEN));

Route::post('/auth', 'AuthController');

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store'])->name('users.store');
});
