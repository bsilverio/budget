<?php

use Illuminate\Http\Request;
use App\Http\Middleware\ValidateRequest;
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
Route::group(['namespace' => 'UserManagement'], function() {
    Route::post("/login","UserController@login");
    Route::post("/logout","UserController@logout");
});

Route::group(['middleware' => ValidateRequest::class], function () {
    Route::group(['namespace' => 'StoreManagement'], function () {
        Route::post("/shop/{id}/execute","StoreController@execute");
        Route::post("/shop","StoreController@create");
        Route::get("/shop/{id}","StoreController@get");
        Route::delete("/shop/{id}","StoreController@delete");
        Route::post("/shop/{id}/robot","StoreController@create_robot");
        Route::put("/shop/{id}/robot/{rid}","StoreController@update_robot");
        Route::delete("/shop/{id}/robot/{rid}","StoreController@delete_robot");
    });
});

Route::get("/", function(Request $request) {
    return response()->json([
        'success' => 1,
        'response' => 'Robot API v1.0'
    ]);
});
