<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;

class SearchController extends Controller
{
    public function index()
    {
        //データ取得
        $spots = Spot::inRandomOrder()->take(6)->get();

        //ビューの表示とデータの引き渡し
        return view('root', compact('spots'));
    }
}
