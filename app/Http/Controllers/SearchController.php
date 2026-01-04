<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;

class SearchController extends Controller
{
    public function index()
    {
        //データ取得
        //$spots = Spot::inRandomOrder()->take(6)->get();

        // 修正後: 人気順表示
        // queriesテーブルの 'to_spot_id' (目的地) に指定された回数が多い順にスポットを取得
        $spots = Spot::query()
            // queriesテーブルを結合 (spots.id と queries.to_spot_id を紐付け)
            ->join('queries', 'spots.id', '=', 'queries.to_spot_id')

            // スポットごとの検索数を集計
            ->select(
                'spots.*',
                DB::raw('count(queries.to_spot_id) as search_count'),
            )
            ->groupBy('spots.id')

            // 検索数が多い順に並べ替え
            ->orderByDesc('search_count')

            // 上位6件を取得
            ->take(6)
            ->get();

        //ビューの表示とデータの引き渡し
        return view('root', compact('spots'));
    }
}
