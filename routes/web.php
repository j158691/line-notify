<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\LineNotifyController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});

#region view
Route::get('/register', function () {return view('register');});
Route::get('/login', function () {return view('login');});
Route::get('/memo', function () {return view('memo');})->middleware('auth');
#endregion

#region register
Route::post('/register', [RegisterController::class, 'postUser']);
#endregion

#region auth
Route::post('/login', [AuthController::class,'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
#endregion

#region memo
Route::post('/memo', [MemoController::class, 'postMemo'])->middleware('auth');
#endregion

#region common
Route::get('/server-time', [CommonController::class, 'getServerTime']);
#endregion

#region lineNotify
Route::get('/authorize', [LineNotifyController::class, 'getAuthorize']);
Route::get('/token', [LineNotifyController::class, 'getOauthToken']);
Route::post('/revoke', [LineNotifyController::class, 'deleteRevoke']); // TODO 懶得幫你解綁
Route::post('/notify', [LineNotifyController::class, 'sendNotify']);
#endregion
