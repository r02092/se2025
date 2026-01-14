<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        // 1. バリデーション
        $credentials = $request->validate([
            'login_name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // 2. ユーザー検索
        $user = User::where('login_name', $credentials['login_name'])->first();

        // 3. 認証チェック
        // ユーザーがいない(null)、またはパスワードが一致しない場合はエラーにする
        if (
            !$user ||
            !password_verify($credentials['password'], $user->password)
        ) {
            throw ValidationException::withMessages([
                'login_name' => __('auth.failed'),
            ]);
        }

        // 4. チェックを通過したらログイン処理
        Auth::login($user);
        $request->session()->regenerate();

        // 5. 2FAチェック
        if (!empty($user->totp_secret)) {
            session(['auth.2fa_required' => true]);
            return redirect()->route('2fa.index');
        }

        // 6. 通常ログイン完了
        return redirect()->intended(route('home'));
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('home');
    }
}
