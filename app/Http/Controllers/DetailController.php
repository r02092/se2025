<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;

class DetailController extends Controller
{
    public function index(Request $request)
    {
        // idに従ってスポットを取得、ない場合はe404
        // 同時にkeyword、review、userのリレーションをあらかじめロードする
        $spot = Spot::with(['keywords', 'reviews.user'])->findOrFail(
            $request->id,
        );

        return view('detail', ['spot' => $spot]);
    }
}
