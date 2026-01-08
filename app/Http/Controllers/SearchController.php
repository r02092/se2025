<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Spot;

class SearchController extends Controller
{
    public function index()
    {
        // 修正後: 人気順表示
        // 1. 先に queries テーブルだけで GROUP BY して、ランキング表（サブクエリ）を作る
        //    (この中では必要な列しか選ばないのでエラーにならない)
        $rankingQuery = DB::table('queries')
            ->select('to_spot_id', DB::raw('count(*) as search_count'))
            ->groupBy('to_spot_id');

        // 2. 作ったランキング表を spots テーブルと結合する
        $spots = Spot::query()
            // joinSub を使うと、サブクエリの結果をテーブルのように扱って結合できます
            ->joinSub($rankingQuery, 'ranking', function ($join) {
                $join->on('spots.id', '=', 'ranking.to_spot_id');
            })

            // 検索数が多い順に並べ替え
            ->orderByDesc('search_count')

            // 上位6件を取得
            ->take(6)
            ->get();

        //ビューの表示とデータの引き渡し
        return view('root', compact('spots'));
    }
}
