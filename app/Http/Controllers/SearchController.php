<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Spot;
use Illuminate\Http\Request; // 追加: リクエスト取得用
use App\Http\Controllers\SearchApiController; // ★APIコントローラーを読み込む

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
     * ★検索実行メソッド
     * SearchApiController を呼び出して検索し、結果を表示します
     */
    public function search(Request $request, SearchApiController $api)
    {
        // 1. 入力値の取得
        $destination = $request->input('destination');
        $departure = $request->input('departure');

        // ▼▼▼ 修正: 出発地の存在チェックを復活 ▼▼▼
        $departureNotFound = false; // 初期値は「見つかった（エラーなし）」

        // 出発地が入力されている場合だけチェックする
        if ($departure) {
            // 名前で検索して、1件もなければエラーフラグを立てる
            $exists = Spot::where('name', 'like', "%{$departure}%")->exists();
            if (!$exists) {
                $departureNotFound = true;
            }
        }
        // ▲▲▲ 修正ここまで ▲▲▲

        // 2. APIコントローラーを使って「目的地」を検索
        $apiRequest = Request::create('/api/filtering', 'GET', [
            'keyword' => $destination,
        ]);

        $response = $api->getSpotList($apiRequest);
        $spotsData = $response->getData(); // 検索結果の取得

        // ▼▼▼ 追加: オブジェクトで返ってきても配列に変換してエラーを防ぐ ▼▼▼
        if (!is_array($spotsData)) {
            $spotsData = (array) $spotsData;
        }
        // 3. 履歴保存 (ランキング用)
        if (count($spotsData) > 0) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                foreach ($spotsData as $spot) {
                    DB::table('queries')->insert([
                        'to_spot_id' => $spot->id,
                        'user_id' => \Illuminate\Support\Facades\Auth::id(),
                        'ip_addr' => $request->ip(),
                        'port' => $request->server('REMOTE_PORT') ?? 0,
                        'query' => $destination,
                        'user_agent' => $request->userAgent(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 4. ビューへ渡す
        return view('search.index', [
            'departure' => $departure,
            'destination' => $destination,
            'spots' => $spotsData,
            'departureNotFound' => $departureNotFound, // ★計算した結果を渡す
        ]);
    }
}
