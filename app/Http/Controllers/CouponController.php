<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\UserCoupon;
use App\Models\Coupon;
use App\Models\Spot;
use App\Traits\ToStringTrait;

class CouponController extends Controller
{
    use ToStringTrait;

    public function get()
    {
        return view('coupon', [
            'couponsList' => array_map(
                function ($i) {
                    $i[2] = $i[2]
                        ->where(function ($q) {
                            $q->where('expires_at', '>=', now())->orWhereNull(
                                'expires_at',
                            );
                        })
                        ->with('spot')
                        ->get()
                        ->map(function ($j) {
                            return [
                                $j,
                                $this->spotTypeToString($j->spot->type),
                                Spot::find($j->cond_spot_id),
                            ];
                        });
                    return $i;
                },
                [
                    [
                        '現在利用中の',
                        'active',
                        Coupon::whereIn(
                            'id',
                            UserCoupon::where('user_id', Auth::user()->id)
                                ->where('is_used', false)
                                ->pluck('coupon_id')
                                ->toArray(),
                        ),
                    ],
                    [
                        '利用可能な',
                        'available',
                        Coupon::whereNotIn(
                            'id',
                            UserCoupon::where('user_id', Auth::user()->id)
                                ->pluck('coupon_id')
                                ->toArray(),
                        ),
                    ],
                ],
            ),
        ]);
    }
}
