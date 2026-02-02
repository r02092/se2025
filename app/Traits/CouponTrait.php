<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Coupon;
use App\Models\UserCoupon;
use Illuminate\Support\Facades\Auth;

trait CouponTrait
{
    public function processCouponAcquisition(int $spotId): array
    {
        // クーポンモジュールを用いて関連クーポン検索
        // couponsテーブルの cond_spot_id (行く必要のあるスポット) が一致するものを探す
        $coupon = Coupon::whereNotIn(
            'id',
            UserCoupon::where('user_id', Auth::id())
                ->pluck('coupon_id')
                ->toArray(),
        )
            ->where('cond_spot_id', $spotId)
            ->where(function ($q) {
                $q->where('expires_at', '>=', now())->orWhereNull('expires_at');
            })
            ->first();

        return $coupon
            ? [
                'success' => true,
                'message' =>
                    'チェックインにより利用可能になったクーポンがあります。',
                'coupon_name' => $coupon->name,
            ]
            : [
                'success' => false,
                'message' => '対象のクーポンはありません。',
                'coupon_name' => null,
            ];
    }
}
