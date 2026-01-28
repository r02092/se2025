<?php

namespace App\Http\Controllers;

use App\Models\Stamp;
use App\Models\Review;

class DataController extends Controller
{
    public function get()
    {
        return view('data', [
            'data' => array_map(
                function ($i) {
                    $i[2] = $i[2]->groupBy('spot_id')->with('spot')->get();
                    return $i;
                },
                [
                    [
                        'スポット',
                        '過去1週間のスタンプ',
                        Stamp::selectRaw(
                            'spot_id, COUNT(spot_id) as count',
                        )->where('created_at', '>', 'NOW() - INTERVAL 1 WEEK'),
                    ],
                    [
                        '口コミが多い場所',
                        '口コミの閲覧合計',
                        Review::selectRaw('spot_id, SUM(views) as count'),
                    ],
                ],
            ),
        ]);
    }
}
