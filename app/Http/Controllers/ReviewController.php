<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * 口コミを投稿する処理
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. 入力チェック (バリデーション)
        $request->validate([
            'spot_id' => 'required|integer|exists:spots,id', // スポットが存在するか
            'rate' => 'required|integer|min:1|max:5', // 評価は1〜5
            'comment' => 'required|string|max:1000', // コメントは必須
        ]);

        // 3. データベースへの保存
        try {
            DB::beginTransaction();

            $review = new Review();
            $review->spot_id = $request->input('spot_id');
            $review->user_id = Auth::id(); // ログインユーザーID
            $review->rate = $request->input('rate');
            $review->comment = $request->input('comment');
            $review->views = 0;

            // ネットワーク情報の記録 (監査ログ用)
            // IPアドレスをバイナリ変換して保存 (VARBINARY型対応)
            $review->ip_addr = inet_pton($request->ip());
            $review->port = $request->server('REMOTE_PORT');
            $review->user_agent = $request->userAgent();

            // ※補足: 現状のDB設計(reviewsテーブル)には画像パスを保存するカラムがありません。
            // 将来的に `img_path` カラム等が追加された場合は、ここで $review->img_path = $imagePath; とします。

            $review->save();

            DB::commit();

            // 4. 元の画面（スポット詳細など）に戻る
            return back()->with('success', '口コミを投稿しました！');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withErrors([
                    'error' => '投稿に失敗しました。もう一度お試しください。',
                ])
                ->withInput();
        }
    }
}
