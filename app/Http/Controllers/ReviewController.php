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
        // 1. 入力チェック (バリデーション)
        $request->validate([
            'spot_id' => 'required|integer|exists:spots,id', // スポットが存在するか
            'rate' => 'required|integer|min:1|max:5', // 評価は1〜5
            'comment' => 'required|string|max:1000', // コメントは必須
        ]);
    }
}
