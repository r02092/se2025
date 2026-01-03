<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use App\Models\ApiKey;
use App\Models\User;
use App\Models\Spot;
use App\Models\Query;
use App\Models\Coupon;
use App\Models\UserCoupon;

class ApiController extends Controller
{
    public function get(ApiRequest $request)
    {
        if (
            !ApiKey::where(
                'key',
                hash('sha3-512', $request->bearerToken(), true),
            )->first()
        ) {
            return response()->json(['error' => '認証に失敗しました。'], 401);
        }
        $spotData = [];
        foreach (Spot::get() as $spot) {
            $id = $spot->id;
            $spotData[] = [
                'id' => $id,
                'type' => $spot->type,
                'name' => $spot->name,
                'lng' => $spot->lng,
                'lat' => $spot->lat,
                'postalCode' => strval($spot->postal_code),
                'addrCity' => $spot->addr_city,
                'addrDetail' => $spot->addr_detail,
                'description' => $spot->description,
                'fromNum' => Query::whereBetween('created_at', [
                    $request->input('from_date'),
                    $request->input('to_date'),
                ])
                    ->where('from_spot_id', $id)
                    ->count(),
                'toNum' => Query::whereBetween('created_at', [
                    $request->input('from_date'),
                    $request->input('to_date'),
                ])
                    ->where('to_spot_id', $id)
                    ->count(),
                'propNum' => $spot->shows,
            ];
        }
        $couponData = [];
        foreach (Coupon::get() as $coupon) {
            $id = $coupon->id;
            $userCoupon = UserCoupon::where('coupon_id', $id);
            $couponData[] = [
                'id' => $id,
                'spotId' => $coupon->spot_id,
                'name' => $coupon->name,
                'acquisitionNum' => $userCoupon->count(),
                'useNum' => $userCoupon->where('is_used', true)->count(),
            ];
        }
        return response()->json([
            'formatVersion' => 1,
            'currentUserNum' => User::count(),
            'spotData' => $spotData,
            'couponData' => $couponData,
        ]);
    }
}
