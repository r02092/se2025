<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;

class SearchController extends Controller
{
    public function index()
    {
        // ① メソッドチェーンによるデータ取得
        $spots = Spot::inRandomOrder()->take(6)->get();

        // ② ビューの表示とデータの引き渡し
        return view('root', compact('spots'));
    }
}
