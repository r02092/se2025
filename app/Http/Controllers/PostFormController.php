<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;

class PostFormController extends Controller
{
    function post(Request $request)
    {
        $post = new Photo();
        $file = $request->file('photo');
        if (!$file->isValid()) {
            return redirect()
                ->back()
                ->withErrors([
                    'error' => '画像ファイルが不正です。',
                ]);
        }
        $post->user_id = auth()->id();
        if (
            !preg_match(
                '/^\[(\d+.\d+),(\d+.\d+)\]$/',
                $request->input('coord'),
                $match,
            )
        ) {
            return redirect()
                ->back()
                ->withErrors([
                    'error' => '位置が不正です。',
                ]);
        }
        $post->lng = $match[1];
        $post->lat = $match[2];
        $ext = $file->getClientOriginalExtension();
        $post->img_ext = $ext;
        $post->comment = $request->input('comment');
        $post->ip_addr = $request->ip();
        $post->port = $request->getPort();
        $post->user_agent = $request->userAgent();
        $post->save();
        $file->storeAs('posts/', $post->id . '.' . $ext, 'public');
        return redirect()->route('post');
    }
}
