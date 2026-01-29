<?php

namespace App\Http\Controllers;

use App\Traits\ToStringTrait;
use App\Models\Coupon;
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
            'type' => $this->spotTypeToString($coupon->spot->type),
            'active' => UserCoupon::where('coupon_id', $coupon->id)
                ->where('user_id', Auth::user()->id)
                ->count(),
        ]);
    }
}
