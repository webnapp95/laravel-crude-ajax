<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/movies/list', 'MoviesController@getList');
Route::get('/movies/{id}', 'MoviesController@getDetail');
Route::post('/movies/createMovies', 'MoviesController@createMovies');
Route::post('/movies/updateMovies/{id}', 'MoviesController@updateMovies');
Route::delete('/movies/{id}', 'MoviesController@deleteMovies');
Route::post('/imageUpload', 'MoviesController@imageUpload');