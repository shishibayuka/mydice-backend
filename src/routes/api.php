<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\API\EventController;

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

// sanctumの保護を受けたルート設定
// api/userにHeadersのAuthorizationにAPIトークンを含めないリクエストを投げるとエラーになる
// APIトークンを含めるとuserのデータを返す
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 認証のチェック
// api_tokenで認証を判別している
// api_tokenを含めないとstatusを401、"message": "Unauthenticated."を返す
// どのuserのtokenか分かるようになっている
// ログインしていないと使えない機能（投稿一覧、新規登録、削除、編集）、URIをここに書く
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/post_courses', [CourseController::class, 'getCoursesByUserId']);
    // courseの新規登録処理
    Route::post('/course', [CourseController::class, 'store']);
});

// ログイン処理
Route::post('/authenticate', [AuthController::class, 'authenticate']);
// Route::post('/user', [AuthController::class, 'user']);

Route::post('/user', [UserController::class, 'create']);
// Route::post('user', 'UserController@create');


// courseに関する処理
Route::get('/course', [CourseController::class, 'index']);

// コースの表示
Route::get('/course/{id}', [CourseController::class, 'show']);
// コースとイベントの削除
Route::delete('/course/{id}', [CourseController::class, 'delete']);
// コースとイベントの編集
Route::patch('/course/{id}', [CourseController::class, 'update']);



// // eventに関する処理（練習用）
// Route::get('/event', [EventController::class, 'index']);
// Route::post('/event', [EventController::class, 'create']);


// // 練習用
// Route::post('/display', [TestController::class, 'display']);


// // / 疎通確認
// Route::get('/ping', function () {
//     Log::debug('pong'); //標準出力にログが出るかの確認
//     return ['pong'];
// });
