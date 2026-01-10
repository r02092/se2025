<?php

namespace App\Traits;

/**
 * 二点間の距離を計算する
 *
 * @param float $lat1 地点1の緯度
 * @param float $lng1 地点1の経度
 * @param float $lat2 地点2の緯度
 * @param float $lng2 地点2の経度
 */
trait DistanceCalculatorTrait
{
    public function calculateDistance(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2,
    ): float {
        $earthRadius = 6371000; //地球の半径（メートル）

        // 緯度経度の差をラジアンに変換
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        // ハーバーサイン公式
        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) *
                cos(deg2rad($lat2)) *
                sin($dLng / 2) *
                sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
