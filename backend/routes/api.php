<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PreferencesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FetchNewsController;


// /* Public routes */
// Route::post('register', [App\Http\Controllers\Api\AuthController::class, 'register']);
// Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);

// /* News & Filters */
// Route::get('/news', [App\Http\Controllers\Api\NewsController::class, 'index']);
// Route::get('/news/filters', [App\Http\Controllers\Api\NewsController::class, 'getFilters']);

// /* Authenticated Routes */
// Route::group(['middleware' => 'auth:sanctum'], function() {
//     // Logout api
//     Route::get('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
// });
Route::post('/register', [AuthController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/logout', [AuthController::class, 'logOut']);
Route::get('/testAPI', [FetchNewsController::class, 'testGetNews']);

Route::group(['middleware' => 'auth.if.has.token'], function () {

    Route::get('/getArticles', [ArticleController::class, 'getArticles']);
    Route::get('/getAuthors', [ArticleController::class, 'getAuthors']);
    Route::get('/getSources', [ArticleController::class, 'getSources']);

});


Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/preferences', [PreferencesController::class, 'getPreferencesPageResources']);
    Route::post('/preferences', [PreferencesController::class, 'savePreferences']);


    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

