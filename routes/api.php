<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Token authentication routes
Route::post('/login', 'ApiAuthenticationController@login');
Route::post('/login/forgot', 'ApiAuthenticationController@forgot');
Route::post('/login/delete', 'ApiAuthenticationController@destroy');

// SPA Routes
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
