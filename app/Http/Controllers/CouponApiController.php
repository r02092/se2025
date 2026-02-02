<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Models\Stamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CouponApiController extends Controller
{
    /**
     * クーポンの利用を開始する
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function use(Request $request)
    {
        // 入力値の検証
        $request->validate([
            'coupon_id' => 'required|integer',
        ]);

        $user = Auth::user();
        $couponId = $request->input('coupon_id');

        $coupon = Coupon::find($couponId);
        $userCoupon = UserCoupon::where('user_id', $user->id)
            ->where('coupon_id', $couponId)
            ->first();
        if (
            $coupon->cond_spot_id &&
            !Stamp::where('user_id', $user->id)
                ->where('spot_id', $coupon->cond_spot_id)
                ->count()
        ) {
            return response()->json(
                ['message' => 'クーポンを使用する権利がありません。'],
                403,
            );
        }

        // 有効期限内であるか？
        $expiresAt = $coupon->expires_at;
        if ($expiresAt && Carbon::now()->gt($expiresAt)) {
            return response()->json(
                ['message' => 'クーポンの有効期限が切れています。'],
                400,
            );
        }

        // 既に使用済み/利用中でないか
        if (!$userCoupon) {
            // 確認用キーの生成とデータベースへの記録
            // 64bit整数の範囲でランダムなキーを生成
            // ※ 本来は衝突チェックが必要ですが、簡易的に生成
            $userCoupon = new UserCoupon();
            $key = rand(0, PHP_INT_MAX);

            $userCoupon->coupon_id = $couponId;
            $userCoupon->user_id = $user->id;
            $userCoupon->key = $key;
            $userCoupon->save();
        }

        // 確認用キーを応答
        return response()->json([
            // JavaScriptで大きな整数を扱う際の精度落ちを防ぐため文字列として返却推奨
            'key' => (string) $userCoupon->key,
        ]);
    }
}
