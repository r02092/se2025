<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;

class SearchApiController extends Controller
{
    public function getSpotList(Request $request)
    {
        // 1. クエリの準備（keywordsとreviewsを一緒に読み込む）
        $query = Spot::with(['keywords', 'reviews']);

        // 2. キーワード検索（名前 OR 説明文 OR タグ）
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');

            // where(function(...)) で囲むことで、AND条件の中にORを含めることができます
            $query->where(function ($q) use ($keyword) {
                // 名前 (name) に含まれているか
                $q->where('name', 'LIKE', "%{$keyword}%")
                    // または、説明文 (description) に含まれているか
                    ->orWhere('description', 'LIKE', "%{$keyword}%")
                    // または、紐づくキーワード (keywordsテーブル) に含まれているか
                    ->orWhereHas('keywords', function ($subQuery) use (
                        $keyword,
                    ) {
                        $subQuery->where('keyword', 'LIKE', "%{$keyword}%");
                    });
            });
        }

        // 3. カテゴリ（種別）での絞り込み（もしあれば）
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // 4. データの取得
        $spots = $query->get();

        // ▼▼▼ 追加: 取得後に優先順位で並び替えるロジック ▼▼▼
        if ($request->filled('keyword')) {
            $spots = $spots
                ->sortByDesc(function ($spot) use ($keyword) {
                    // 1. 名前が完全一致なら最強 (100点)
                    if ($spot->name === $keyword) {
                        return 100;
                    }
                    // 2. 名前にキーワードが含まれていれば (50点)
                    if (str_contains($spot->name, $keyword)) {
                        return 50;
                    }
                    // 3. キーワードタグに含まれていれば (30点)
                    foreach ($spot->keywords as $k) {
                        if (str_contains($k->keyword, $keyword)) {
                            return 30;
                        }
                    }
                    // 4. それ以外（説明文だけヒットなど）は (10点)
                    return 10;
                })
                ->values(); // キーを連番に振り直す
        }

        // 5. JSON形式に整形して返す
        // (APIとしても、SearchControllerから呼ばれた場合も使いやすい形にする)
        $result = $spots->map(function (\App\Models\Spot $spot) {
            return [
                'id' => $spot->id,
                'name' => $spot->name,
                // ▼▼▼ 追加: テストコードを通すために必要なデータ ▼▼▼
                'user_id' => $spot->user_id,
                'plan' => $spot->plan,
                'description' => $spot->description,
                'type' => $spot->type,
                // 画像URLの生成ロジック
                'image_url' => asset(
                    'images/' . $spot->name . '.' . ($spot->img_ext ?? 'jpg'),
                ),
                'keywords' => $spot->keywords->pluck('keyword')->toArray(),
            ];
        });

        // JSONレスポンスとして返す
        return response()->json($result);
    }
}
