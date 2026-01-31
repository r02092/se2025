<?php

namespace App\Http\Controllers;

use App\Traits\ToStringTrait;
use App\Models\Coupon;
use App\Models\Spot;
use App\Models\Stamp;
use App\Models\UserCoupon;
use Illuminate\Support\Facades\Auth;

class CouponSelectedController extends Controller
{
    use ToStringTrait;

    public function get($id)
    {
        $coupon = Coupon::with('spot')->find($id);
        return view('coupon-selected', [
            'coupon' => $coupon,
            'condSpotName' => $coupon->cond_spot_id
                ? Spot::find($coupon->cond_spot_id)->name
                : null,
            'type' => $this->spotTypeToString($coupon->spot->type),
            'available' =>
                !$coupon->cond_spot_id ||
                Stamp::where('user_id', Auth::id())
                    ->where('spot_id', $coupon->cond_spot_id)
                    ->count(),
            'active' => UserCoupon::where('coupon_id', $coupon->id)
                ->where('user_id', Auth::id())
                ->count(),
        ]);
    }
}
