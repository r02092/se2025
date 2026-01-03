<?php

namespace App\Http\Controllers;

use APP\Models\Spot;
use APP\Models\Keyword;
use Illuminate\Http\Request;

class SearchApiController extends Controller
{
    public function getSpotList(Request $request)
    {
        // クライアントからのキーワードとカテゴリの入力を取得
        // inputの引数は仮
        $keyword = $request->input('keyword');
        $type = $request->input('type');

        // カテゴリとキーワードの両方が指定されていない場合、エラーレスポンスを返す
        if (!$keyword && !$type) {
            return response()->json(
                [
                    'error' =>
                        'キーワードまたはカテゴリのいずれかを指定してください。',
                ],
                400,
            );
        }

        $spots = Spot::where('type', $type)->get();

        //キーワードが指定されている場合、キーワードに基づいてフィルタリング
        if ($keyword) {
            $spots = $spots->filter(function ($spot) {
                return $spot->keywords->contains('keyword', $keyword);
            });
        }

        return response()->json($spots);
    }
}
