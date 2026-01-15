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
        // 1. フォームの入力値を取得
        $destination = $request->input('destination'); // 画面の入力欄は "destination"
        $departure = $request->input('departure');

        // 2. APIコントローラー用のリクエストを作成
        // SearchApiController は "keyword" という名前で入力を待っているので、名前を合わせます
        $apiRequest = Request::create('/api/filtering', 'GET', [
            'keyword' => $destination, // destination を keyword として渡す
            // 'type' => ... 必要ならここに追加
        ]);

        // 3. APIコントローラーのメソッドをそのまま呼び出す
        // 書き換えられない SearchApiController::getSpotList() を実行します
        $response = $api->getSpotList($apiRequest);

        // 4. 返ってきた JSON データを取り出す
        $spotsData = $response->getData(); // JSONをPHPのオブジェクト配列に変換

        // 5. ランキング集計のために履歴を保存
        // (API側には保存機能がないため、ここで保存します)
        if (count($spotsData) > 0) {
            foreach ($spotsData as $spot) {
                DB::table('queries')->insert([
                    'to_spot_id' => $spot->id,
                    'query' => $destination,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 6. 画面 (ビュー) にデータを渡す
        // APIからのデータは配列になっているので、ビュー側で使いやすいように調整して渡します
        return view('search.index', [
            'departure' => $departure,
            'destination' => $destination,
            'spots' => $spotsData, // APIが見つけたスポット一覧
            'departureNotFound' => false, // 簡易化のため一旦false
        ]);
    }
}
