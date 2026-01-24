<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SearchApiController; // ★APIコントローラーを読み込む

class SearchController extends Controller
{
    public function index()
    {
        // 人気順表示 (変更なし)
        $rankingQuery = DB::table('queries')
            ->select('to_spot_id', DB::raw('count(*) as search_count'))
            ->whereNotNull('to_spot_id')
            ->groupBy('to_spot_id');

        $spots = Spot::query()
            ->joinSub($rankingQuery, 'ranking', function ($join) {
                $join->on('spots.id', '=', 'ranking.to_spot_id');
            })
            ->orderByDesc('search_count')
            ->take(5)
            ->get();

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

        // 出発地の存在チェック
        $departureNotFound = false;
        if ($departure) {
            $exists = Spot::where('name', 'like', "%{$departure}%")->exists();
            if (!$exists) {
                $departureNotFound = true;
            }
        }

        // 2. ★APIコントローラーを使って「目的地」を検索
        // 内部リクエストを作成してAPIに渡す
        $apiRequest = Request::create('/api/filtering', 'GET', [
            'keyword' => $destination,
        ]);

        // API実行 (優先順位付きの結果が返ってくる)
        $response = $api->getSpotList($apiRequest);
        $spotsData = $response->getData(); // オブジェクト配列として取得

        // 配列変換（念のため）
        if (!is_array($spotsData)) {
            $spotsData = (array) $spotsData;
        }

        // 3. 履歴保存
        // 検索結果の1件目（最も優先度が高いスポット）を履歴として保存
        if (count($spotsData) > 0 && Auth::check()) {
            // getData()で返ってくるのはstdClassの配列なので ->id でアクセス
            $topSpot = $spotsData[0];

            DB::table('queries')->insert([
                'user_id' => Auth::id(),
                'query' => $destination,
                'to_spot_id' => $topSpot->id, // ヒットした最上位のスポットID
                'ip_addr' => $request->ip(),
                'port' => $request->server('REMOTE_PORT') ?? 0,
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. ビューへ渡す
        return view('search.index', [
            'departure' => $departure,
            'destination' => $destination,
            'spots' => $spotsData,
            'departureNotFound' => $departureNotFound,
        ]);
    }

    /**
     * AIプランニング画面を表示する
     */
    public function aiPlan(Request $request, SearchApiController $api)
    {
        // 1. 入力値を取得
        $depName =
            $request->input('departure') ?? $request->input('departure_name');
        $dstName =
            $request->input('destination') ??
            $request->input('destination_name');

        // 2. APIを使って最適なスポットを1件特定するヘルパー関数
        // (SearchController内で独自に検索せず、APIのロジックを再利用する)
        $findSpotViaApi = function ($text) use ($api) {
            if (empty($text)) {
                return null;
            }

            // APIリクエスト作成
            $req = Request::create('/api/filtering', 'GET', [
                'keyword' => $text,
            ]);
            // API実行
            $res = $api->getSpotList($req);
            $data = $res->getData();

            // 結果があれば先頭（最もスコアが高いスポット）を返す
            if (count($data) > 0) {
                // stdClassをSpotモデルに変換（またはIDだけ取得してFind）しても良いが、
                // ビュー側でプロパティとして使うならstdClassのままでもOK。
                // ここでは後続処理の安全性のためIDからモデルを取得し直す
                return Spot::find($data[0]->id);
            }
            return null;
        };

        // 3. スポットを特定
        $fromSpot = $findSpotViaApi($depName);
        $toSpot = $findSpotViaApi($dstName);

        // 4. ビューを表示
        return view('search.ai-plan', [
            'depName' => $depName,
            'dstName' => $dstName,
            'fromSpot' => $fromSpot,
            'toSpot' => $toSpot,
        ]);
    }
}
