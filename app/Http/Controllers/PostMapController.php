<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;

class PostMapController extends Controller
{
    function get()
    {
        // 仮に全部送信
        $posts = Photo::all();
        return view('post', ['posts' => $posts]);
    }
}
