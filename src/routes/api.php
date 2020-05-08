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

// 인증이 필요한 API endpoint (Sanctum 이용)
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/logout', 'Auth\LoginController@logout'); // 로그아웃
    Route::get('/user/{id}', 'UserController@getUser'); // 단일 회원 상세 정보 조회
    Route::get('/user/{id}/orders', 'UserController@getOrders'); // 단일 회원의 주문목록 조회
    Route::post('/users', 'UserController@getUsers'); // 여러 회원 목록 조회
});

// 인증이 불필요한 API endpoint
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('/register', 'Auth\RegisterController@register'); // 회원가입
    Route::post('/login', 'Auth\LoginController@login'); // 로그인
});
