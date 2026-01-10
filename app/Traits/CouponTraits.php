<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Coupon;
use App\Models\UserCoupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait CouponTrait
{
    public function processCouponAcquisition(int $spotId): array
    {
        // 1. クーポンモジュールを用いて関連クーポン検索
        // couponsテーブルの cond_spot_id (行く必要のあるスポット) が一致するものを探す
        $coupon = Coupon::where('cond_spot_id', $spotId)->first();

        // 2. 対象クーポンがあるか
        if (!$coupon) {
            return [
                'success' => false,
                'message' => '対象のクーポンはありません。',
                'coupon_name' => null,
            ];
        }

        // 3. 有効期限内であるか？
        // expires_at が現在時刻より未来であるか確認
        if ($coupon->expires_at && Carbon::now()->gt($coupon->expires_at)) {
            return [
                'success' => false,
                'message' => 'クーポンの有効期限が切れています。',
                'coupon_name' => $coupon->name,
            ];
        }

        $userId = Auth::id();

        // ユーザーが既にこのクーポンを所持しているか確認（重複受け取り防止）
        $exists = UserCoupon::where('user_id', $userId)
            ->where('coupon_id', $coupon->id)
            ->exists();

        if ($exists) {
            return [
                'success' => false,
                'message' => '既にクーポンを獲得済みです。',
                'coupon_name' => $coupon->name,
            ];
        }

        // 4. 利用者クーポンモジュールを用いてデータベースに記録
        // user_coupons テーブルへ保存
        // key (確認用キー) は BIGINT UNSIGNED なのでランダムな数値を生成
        try {
            UserCoupon::create([
                'coupon_id' => $coupon->id,
                'user_id'   => $userId,
                'key'       => random_int(1000000000, 9999999999), // 簡易的なユニークキー生成
                'is_used'   => 0, // 未使用
            ]);

            // 5. 獲得に成功した旨を返す
            return [
                'success' => true,
                'message' => 'クーポンを獲得しました！',
                'coupon_name' => $coupon->name,
            ];

        } catch (\Exception $e) {
            // 獲得に失敗した旨を返す
            return [
                'success' => false,
                'message' => 'クーポンの獲得処理に失敗しました。',
                'coupon_name' => $coupon->name,
            ];
        }
    }
}