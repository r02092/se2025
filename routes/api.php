<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CouponApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| API用のルート定義ファイルです。
| 認証機能(Sanctum)を経由してアクセスするルートなどをここに記述します。
|
*/

// 認証が必要なグループ
Route::middleware('auth:sanctum')->group(function () {
    // ユーザー情報取得（テスト用）
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // ▼▼▼ 今回追加する部分 ▼▼▼
    // MU17 クーポン利用開始API
    Route::post('/coupon/use', [CouponApiController::class, 'use']);
});
