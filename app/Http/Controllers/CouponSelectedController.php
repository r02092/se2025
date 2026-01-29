<?php

namespace App\Http\Controllers;

use App\Traits\ToStringTrait;
use App\Models\Coupon;
use App\Models\UserCoupon;
use Illuminate\Support\Facades\Auth;

class CouponSelectedController extends Controller
{
    use ToStringTrait;

    public function get($coupon)
    {
        return view('coupon-selected', [
            'coupon' => Coupon::with('spot')->find($coupon),
            'active' => UserCoupon::where('coupon_id', $coupon)
                ->where('user_id', Auth::user()->id)
                ->count(),
            'types' => $this->types,
        ]);
    }
}
