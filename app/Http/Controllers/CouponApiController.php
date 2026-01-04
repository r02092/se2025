<?php

namespace App\Http\Controllers;

use App\Models\UserCoupon;
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

        // 1. 利用者が所持しているか確認
        $userCoupon = UserCoupon::where('user_id', $user->id)
            ->where('coupon_id', $couponId)
            ->with('coupon') // 有効期限確認のためにクーポン情報も取得
            ->first();

        if (!$userCoupon) {
            return response()->json(
                ['message' => 'クーポンを所持していません。'],
                404,
            );
        }

        // 2. 利用可能か？（既に使用済み/利用中でないか）
        if ($userCoupon->is_used) {
            return response()->json(
                ['message' => 'このクーポンは既に使用されています。'],
                400,
            );
        }

        // 3. 有効期限内であるか？
        $expiresAt = $userCoupon->coupon->expires_at;
        if ($expiresAt && Carbon::now()->gt($expiresAt)) {
            return response()->json(
                ['message' => 'クーポンの有効期限が切れています。'],
                400,
            );
        }

        // 4. 確認用キーの生成とデータベースへの記録
        // 64bit整数の範囲でランダムなキーを生成
        // ※ 本来は衝突チェックが必要ですが、簡易的に生成
        $key = random_int(100000, 9223372036854775807);

        $userCoupon->key = $key;
        $userCoupon->is_used = true; // 利用中状態へ更新
        $userCoupon->save();

        // 5. 確認用キーを応答
        return response()->json([
            'message' => 'クーポンの利用を開始しました。',
            // JavaScriptで大きな整数を扱う際の精度落ちを防ぐため文字列として返却推奨
            'key' => (string) $key,
        ]);
    }
}
