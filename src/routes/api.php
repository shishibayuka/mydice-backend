<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TestController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/authenticate', [AuthController::class, 'authenticate']);
// Route::post('/user', [AuthController::class, 'user']);

Route::post('/user', [UserController::class, 'create']);
// Route::post('user', 'UserController@create');

Route::post('/display', [TestController::class, 'display']);

// / 疎通確認
Route::get('/ping', function () {
    Log::debug('pong'); //標準出力にログが出るかの確認
    return ['pong'];
});
