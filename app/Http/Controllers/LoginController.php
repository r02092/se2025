<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function index()
    {
        return view('login'); // ログイン画面を表示
    }
    public function authenticate(Request $request)
    {
        // バリデーション
        $credentials = $request->validate([
            'login_name' => ['required', 'string'],
            'password'   => ['required', 'string'],
        ]);

        // 認証試行 (Auth::attempt はハッシュ化されたパスワードを自動で照合)
        if (!Auth::attempt($credentials)) {
            // 認証失敗
            throw ValidationException::withMessages([
                'login_name' => __('auth.failed'), // "認証情報が記録と一致しません"
            ]);
        }
        $request->session()->regenerate();

        // 二要素認証チェック
        $user = Auth::user();
        
        if (!empty($user->totp_secret)) {
            // 2FAが必要な場合、フラグを立てて2FA画面へ
            session(['auth.2fa_required' => true]);
            return redirect()->route('2fa.index');
        }

        // 通常ログイン完了
        return redirect()->intended(route('home'));
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}