<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
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
        // 入力チェック (バリデーション)
        $request->validate(
            [
                'spot_id' => 'required|integer|exists:spots,id', // スポットが存在するか
                'rate' => 'required|integer|min:1|max:5', // 評価は1〜5
                'comment' => 'required|string|max:1000', // コメントは必須
            ],
            [
                'comment.max' => 'コメントは1～1000文字で入力してください',
            ],
        );

        // データベースに投稿内容を保存する
        Review::create([
            'spot_id' => $request->input('spot_id'),
            'user_id' => auth()->id(),
            'rate' => $request->input('rate'),
            'comment' => $request->input('comment'),
            'views' => 0,
            'ip_addr' => $request->ip() ?? '192.0.2.0',
            'port' => $request->getPort() ?? 0,
            'user_agent' => $request->userAgent() ?? '',
        ]);

        // 元の画面（スポット詳細など）に戻る
        return back()->with('success', '口コミを投稿しました！');
    }
}
