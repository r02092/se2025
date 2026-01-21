<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\UserCoupon;
use App\Models\Coupon;

class CouponController extends Controller
{
    use \App\Traits\ToStringTrait;

    public function get()
    {
        $activeCouponIds = UserCoupon::where('user_id', Auth::user()->id)
            ->where('is_used', false)
            ->pluck('coupon_id')
            ->toArray();
        return view('coupon', [
            'couponsList' => array_map(
                function ($i) {
                    return [
                        $i[0],
                        $i[1],
                        $i[2]
                            ->where('expires_at', '>=', now())
                            ->orWhereNull('expires_at')
                            ->with('spot')
                            ->get()
                            ->map(function ($j) {
                                return [
                                    $j,
                                    $this->spotTypeToString($j->spot->type),
                                ];
                            }),
                    ];
                },
                [
                    [
                        '現在利用中の',
                        'active',
                        Coupon::whereIn('id', $activeCouponIds),
                    ],
                    [
                        '利用可能な',
                        'available',
                        Coupon::whereNotIn('id', $activeCouponIds),
                    ],
                ],
            ),
        ]);
    }
}
