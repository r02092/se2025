<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;

class SearchApiController extends Controller
{
    public function getSpotList(Request $request)
    {
        // クライアントからのキーワードとカテゴリの入力を取得
        // inputの引数は仮
        $keyword = $request->input('keyword');
        $type = $request->input('type');

        $spots = Spot::all();

        // カテゴリが指定されている場合、カテゴリに基づいてフィルタリング
        if (isset($type)) {
            $spots = $spots->filter(function ($spot) use ($type) {
                return $spot->type === $type;
            });
        }

        //キーワードが指定されている場合、キーワードに基づいてフィルタリング
        if (isset($keyword)) {
            $spots = $spots->filter(function ($spot) use ($keyword) {
                return $spot->keywords->contains('keyword', $keyword);
            });
        }

        return response()->json($spots);
    }
}
