<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response(status: Response::HTTP_FORBIDDEN));

Route::post('/auth', 'AuthController');
