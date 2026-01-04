<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;

class PostMapController extends Controller
{
    function get()
    {
        $posts = Photo::all();
        return view('post', ['posts' => $posts]);
    }
}
