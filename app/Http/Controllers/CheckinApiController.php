<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use App\Models\Stamp;
use App\Traits\CouponTrait;
use App\Traits\DistanceCalculatorTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CheckinApiController extends Controller
{
    use CouponTrait;
    use DistanceCalculatorTrait;

    public function __invoke(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'stamp_key' => ['required', 'integer'],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => '入力情報が不正です。',
                    'details' => $validator->errors(),
                ],
                400,
            );
        }
        $stampKey = $request->input('stamp_key');
        $userLat = (float) $request->input('lat');
        $userLng = (float) $request->input('lng');

        $spot = Spot::where('stamp_key', $stampKey)->first();

        if (!$spot) {
            return response()->json(
                ['error' => '該当するスポットが見つかりません。'],
                404,
            );
        }

        $distance = $this->calculateDistance(
            $userLat,
            $userLng,
            (float) $spot->lat,
            (float) $spot->lng,
        );
        $thresholdMeters = 500;

        if ($distance > $thresholdMeters) {
            return response()->json(
                [
                    'error' =>
                        'スポットから離れすぎています。現地に近づいて再度お試しください。',
                ],
                400,
            );
        }
        return DB::transaction(function () use ($spot, $request) {
            $userId = Auth::id();
            $exists = Stamp::where('spot_id', $spot->id)
                ->where('user_id', $userId)
                ->exists();

            if ($exists) {
                return response()->json(
                    ['message' => 'すでにチェックイン済みです。'],
                    200,
                );
            }

            Stamp::create([
                'spot_id' => $spot->id,
                'user_id' => $userId,
                'ip_addr' => $request->ip(),
                'port' => $request->server('REMOTE_PORT', 0),
                'user_agent' => $request->userAgent() ?? 'unknown',
            ]);

            $couponResult = $this->processCouponAcquisition($spot->id);

            return response()->json(
                [
                    'message' => 'チェックインに成功しました。',
                    'spot_name' => $spot->name,
                    'coupon_result' => $couponResult,
                ],
                200,
            );
        });
    }
}
