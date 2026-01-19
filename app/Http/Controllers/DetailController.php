<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;

class DetailController extends Controller
{
    public function get(Request $request)
    {
        // idに従ってスポットを取得
        // 同時にkeyword、review、userのリレーションをあらかじめロードする
        $spot = Spot::with(['keywords', 'reviews.user.name'])->find(
            $request->input('id'),
        );

        return view('spot-detail', ['spot' => $spot]);
    }
}
