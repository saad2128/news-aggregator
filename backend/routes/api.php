<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\PreferencesController;
use App\Http\Controllers\Api\FetchNewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/logout', [AuthController::class, 'logOut']);
Route::get('/news', [FetchNewsController::class, 'fetchNews']);

Route::group(['middleware' => 'auth.if.has.token'], function () {

    Route::get('/articles', [ArticleController::class, 'getArticles']);
    Route::get('/authors', [ArticleController::class, 'getAuthors']);
    Route::get('/sources', [ArticleController::class, 'getSources']);

});


Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/preferences', [PreferencesController::class, 'getPreferencesPageResources']);
    Route::post('/preferences', [PreferencesController::class, 'savePreferences']);


    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

