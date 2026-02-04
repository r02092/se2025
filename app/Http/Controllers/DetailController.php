<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use App\Traits\ToStringTrait;

class DetailController extends Controller
{
    use ToStringTrait;

    public function index(Request $request)
    {
        // idに従ってスポットを取得、ない場合はe404
        // 同時にkeyword、review、userのリレーションをあらかじめロードする
        $spot = Spot::with([
            'keywords',
            'reviews' => function ($query) {
                $query->orderBy('updated_at', 'desc');
            },
            'reviews.user',
        ])->findOrFail($request->id);

        // 口コミの閲覧数を増やす
        $spot->reviews->each(function ($item, $key) {
            $item->views++;
            $item->save();
        });

        // カテゴリの文字列を取得
        $typeStr = $this->spotTypeToString($spot->type);

        // 住所を取得
        $postal_code = $this->postalCodeToString($spot->postal_code);
        $addrStr = $this->cityToString($spot->addr_city);
        $addrStr .= $spot->addr_detail;

        return view('detail', [
            'spot' => $spot,
            'typeStr' => $typeStr,
            'postal_code' => $postal_code,
            'addrStr' => $addrStr,
        ]);
    }
}
