<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Spot;
use use Illuminate\Http\Request; // 追加: リクエスト取得用

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
        return view('home', compact('spots'));
    }

    /**
     * ★追加: 検索実行用メソッド
     * 検索ボタンが押されたときに動く処理です
     */
    public function search(Request $request)
    {
        // 1. 入力値の取得
        $departure = $request->input('departure');     // 出発地
        $destination = $request->input('destination'); // 目的地（検索ワード）

        // バリデーション
        $request->validate([
            'destination' => 'required', // 目的地は必須
        ]);

        // 2. スポット検索 (名前であいまい検索)
        $spots = Spot::where('name', 'like', "%{$destination}%")->get();

        // 3. 【重要】ランキングのために検索履歴を保存
        // スポットが見つかった場合、そのIDを queries テーブルに記録します。
        // これにより、トップページのランキング(indexメソッド)に反映されるようになります。
        if ($spots->count() > 0) {
            foreach ($spots as $spot) {
                DB::table('queries')->insert([
                    'to_spot_id' => $spot->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 4. 検索結果ビューを表示
        // (resources/views/search/index.blade.php を表示します)
        return view('search.index', compact('departure', 'destination', 'spots'));
    }
}
