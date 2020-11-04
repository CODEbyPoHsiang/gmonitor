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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//預設進入dashboard 半小時資料
Route::get('gmonitor', 'GmonitorController@index');

// 搜尋api 要帶條件"參數"
//時間區間 + WAN (選擇 WAN1、WAN2或WAN3) + 監控項(Default_DNS、PING_8.8.8.8、PING_168.95.1.1、PING_103.24.83.242、PING)
Route::post('gmonitor_search_data', 'GmonitorController@search_data');
