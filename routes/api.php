<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', 'ApiAuthenticationController@login');
Route::post('/login/forgot', 'ApiAuthenticationController@forgot');

// SPA Routes
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
