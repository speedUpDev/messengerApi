<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('/user')->group(function (){
    Route::post('/login', 'App\Http\Controllers\LoginController@login');
    Route::post('/register', 'App\Http\Controllers\RegisterController@save');
    Route::middleware('auth:api')->get('/me', function (Request $request){
        return $request->user()->makeVisible(['email']);
    });
});
Route::apiResources([
    'messages'=>\App\Http\Controllers\MessageController::class,
]);


