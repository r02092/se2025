<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Spot;

class SearchController extends Controller
{
    /**
     * トップページ表示 (ランキング)
     */
    public function index()
    {
        // ランキング集計
        $rankingQuery = DB::table('queries')
            ->select('to_spot_id', DB::raw('count(*) as search_count'))
            ->groupBy('to_spot_id');

        $spots = Spot::query()
            ->joinSub($rankingQuery, 'ranking', function ($join) {
                $join->on('spots.id', '=', 'ranking.to_spot_id');
            })
            ->orderByDesc('search_count')
            ->take(6)
            ->get();

        return view('home', compact('spots'));
    }

    /**
     * 検索実行メソッド
     * APIを使わず、ここで直接「あいまい検索」を行います
     */
    public function search(Request $request)
    {
        // 1. 入力値の取得
        $destination = $request->input('destination');
        $departure = $request->input('departure');

        // バリデーション
        $request->validate([
            'destination' => 'required',
        ]);

        // 2. 目的地を検索 (名前、または説明文にあいまい一致するもの)
        $spots = Spot::where('name', 'like', "%{$destination}%")
            ->orWhere('description', 'like', "%{$destination}%")
            ->get();

        // 3. 出発地のチェック (入力がある場合のみ)
        $departureNotFound = false;
        if ($departure) {
            $exists = Spot::where('name', 'like', "%{$departure}%")->exists();
            if (!$exists) {
                $departureNotFound = true;
            }
        }

        // 4. 履歴保存 (ランキング用)
        if ($spots->count() > 0) {
            foreach ($spots as $spot) {
                DB::table('queries')->insert([
                    'to_spot_id' => $spot->id,
                    'query' => $destination,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 5. ビューへ渡す
        return view('search.index', [
            'departure' => $departure,
            'destination' => $destination,
            'spots' => $spots,
            'departureNotFound' => $departureNotFound,
        ]);
    }
}
