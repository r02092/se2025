<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;

class SearchApiController extends Controller
{
    public function getSpotList(Request $request)
    {
        // 1. クエリの準備
        $query = Spot::with(['keywords', 'reviews']);

        // ▼▼▼ 修正: 'keyword' または 'destination' どちらでも受け取れるようにする ▼▼▼
        // (Blade側で destination を使っているため)
        $rawKeyword =
            $request->input('keyword') ?? $request->input('destination');

        if (!empty($rawKeyword)) {
            // ▼▼▼ 修正: 配列で来た場合の対策 (Array to string conversionエラー防止) ▼▼▼
            if (is_array($rawKeyword)) {
                // 配列ならスペース区切りの文字列に変換
                $keyword = implode(' ', $rawKeyword);
            } else {
                $keyword = (string) $rawKeyword;
            }

            // where(function(...)) で囲むことで、AND条件の中にORを含めることができます
            $query->where(function ($q) use ($keyword) {
                // 名前 (name) に含まれているか
                $q->where('name', 'LIKE', "%{$keyword}%")
                    // または、説明文 (description) に含まれているか
                    ->orWhere('description', 'LIKE', "%{$keyword}%")
                    // または、紐づくキーワード (keywordsテーブル) に含まれているか
                    // ▼▼▼ 修正: useの中のカンマを削除 ▼▼▼
                    ->orWhereHas('keywords', function ($subQuery) use (
                        $keyword,
                    ) {
                        $subQuery->where('keyword', 'LIKE', "%{$keyword}%");
                    });
            });
        }

        // 3. カテゴリ（種別）での絞り込み
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // 4. データの取得
        $spots = $query->get(['id', 'name', 'description', 'img_ext']);

        // ▼▼▼ 追加: 取得後に優先順位で並び替えるロジック ▼▼▼
        // $keyword が存在する場合のみ実行
        if (!empty($rawKeyword)) {
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
                    // null安全演算子 (?->) を使ってエラー回避
                    foreach ($spot->keywords ?? [] as $k) {
                        if (str_contains($k->keyword, $keyword)) {
                            return 30;
                        }
                    }
                    // 4. それ以外（説明文だけヒットなど）は (10点)
                    return 10;
                })
                ->values(); // キーを連番に振り直す
        }

        // JSONレスポンスとして返す
        return response()->json($spots);
    }
}
