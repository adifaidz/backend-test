<?php

use Illuminate\Http\Request;
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
Route::get('posts/top', 'PostController@top')->name('posts.top');
Route::apiResource('posts', 'PostController')->only(['index', 'show']);

Route::apiResource('comments', 'CommentController')->only(['index']);

Route::fallback(function() {
    return response()->json([
        'message'=> 'Resource Not Found'
    ],404);
});

