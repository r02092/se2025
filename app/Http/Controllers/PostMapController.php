<?php

namespace App\Http\Controllers;

use App\Models\Photo;

class PostMapController extends Controller
{
    function index()
    {
        // 仮に全部送信
        $posts = Photo::all();
        return view('photo', ['posts' => $posts]);
    }
}
