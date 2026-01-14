<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        // 1. 利用者モジュールを用いて、現在認証されている利用者の情報を読み込み
        $user = Auth::user();

        // 2. プロフィール画面出力モジュール (MV04: profile.blade.php) を呼び出し
        return view('profile', [
            'user' => $user,
        ]);
    }
}
