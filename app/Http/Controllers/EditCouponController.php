<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;
use Illuminate\Http\Request;

class EditCouponController extends Controller
{
    public function get($spotId)
    {
        $spot = Spot::find($spotId);
        if (Auth::id() !== $spot->user_id) {
            return response()->json(
                [
                    'error' => '権限がありません。',
                ],
                403,
            );
        }
        return view('coupon-edit', [
            'spot' => $spot,
            'coupons' => $spot->coupons
                ->sortByDesc('created_at')
                ->values()
                ->map(function ($i) {
                    $condSpot = Spot::find($i->cond_spot_id);
                    return [$i, $condSpot ? $condSpot->name : ''];
                }),
        ]);
    }
    public function update(Request $request)
    {
        if (!preg_match('/\/(\d+?)$/', url()->previous(), $match)) {
            return response()->json(
                [
                    'error' => 'リファラが不正です。',
                ],
                400,
            );
        }
        $spotId = $match[1];
        if (isset($request->id)) {
            $coupon = Coupon::find($request->id);
        } else {
            $coupon = new Coupon();
            $coupon->spot_id = $spotId;
        }
        if (Auth::id() !== Spot::find($coupon->spot_id)->user_id) {
            return response()->json(
                [
                    'error' => '権限がありません。',
                ],
                403,
            );
        }
        $coupon->name = $request->name;
        if ($condSpot = Spot::where('name', $request->cond)->first()) {
            $coupon->cond_spot_id = $condSpot->id;
        }
        $coupon->expires_at = $request->expires;
        $coupon->save();
        return redirect()->route('business.coupon', $spotId);
    }
    public function delete(Request $request)
    {
        $coupon = Coupon::with('spot')->find($request->id);
        if (Auth::id() !== $coupon->spot->user_id) {
            return response()->json(
                [
                    'error' => '権限がありません。',
                ],
                403,
            );
        }
        $spotId = $coupon->spot_id;
        $coupon->delete();
        return redirect()->route('business.coupon', $spotId);
    }
}
