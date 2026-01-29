<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCoupon;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CouponCheckApiController extends Controller
{
    public function post(Request $request)
    {
        $request->validate([
            'key' => 'required|integer',
        ]);
        $userCoupon = UserCoupon::where('key', $request->key)
            ->with('coupon.spot')
            ->first();
        if (!$userCoupon) {
            return response('不正なクーポンです。', 400);
        }
        if (Auth::user()->id !== $userCoupon->coupon->spot->user_id) {
            return response('他の事業者が発行したクーポンです。', 400);
        }
        $expiresAt = $userCoupon->coupon->expires_at;
        if ($expiresAt && Carbon::now()->gt($expiresAt)) {
            return response('期限切れのクーポンです。', 400);
        }
        if ($userCoupon->is_used) {
            return response('使用済みのクーポンです。', 400);
        }
        $userCoupon->is_used = true;
        $userCoupon->save();
        return response(
            'クーポン「' . $userCoupon->coupon->name . '」を確認しました。',
        );
    }
}
