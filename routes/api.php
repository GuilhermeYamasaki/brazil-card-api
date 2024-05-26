<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response(status: Response::HTTP_FORBIDDEN));

Route::post('/auth', 'AuthController');

Route::prefix('users')->group(function () {
    Route::post('/', 'UserStoreController')->name('users.store');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/money/charge', 'MoneyChargeController')->name('money.charge');
    Route::post('/money/transfer', 'MoneyTransferController')->name('money.transfer');
});

Route::prefix('webhook')->group(function () {
    Route::post('/asaas', 'WehbookAsaasController')->name('webhook.asaas');
});
