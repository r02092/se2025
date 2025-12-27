<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function get()
    {
        return view('login'); // ログインビューを返す
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([ // バリデーション
            'login_name' => ['required', 'string'], // 必須かつString型
            'password' => ['required', 'string'],
        ]);
        if (!Auth::attempt([ // 認証試行
            'login_name' => $credentials['login_name'],
            'password' => $credentials['password'],
            'provider' => User::PROVIDER_SCENETRIP,
        ], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'login_name' => __('auth.failed'),
            ]);
        }
        $request->session()->regenerate(); // セッション再生成
        return $this->handleTwoFactor($request);
    }
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect(); // Googleのログイン画面に転送
    }
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'login_name' => 'Googleログインに失敗しました。',
            ]);
        }
        $user = User::where('provider', User::PROVIDER_GOOGLE) // プロバイダがGoogleで
            ->where('login_name', $googleUser->getID()) // idが一致する
            ->first(); // 最初のひとりを取得
        if (!$user) { // ユーザーが存在しない場合、新規作成
            $user = User::create([
                'provider' => User::PROVIDER_GOOGLE,
                'login_name' => $googleUser->getID(),
                'name' => $googleUser->getName() ?? 'No Name', 
                'password' => null, // パスワードは不要
                'permission' => User::PERMISSION_USER,
                'num_plan_std' => 0,
                'num_plan_prm' => 0,
                'icon_ext' => 'png',
                // 他の必要なフィールドもここで初期化
            ]);
        }
        Auth::login($user, true);
        $request->session()->regenerate();
        return $this->handleTwoFactorCheck($request);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate(); // セッション無効化
        $request->session()->regenerateToken(); // CSRFトークン再生成
        return redirect('/'); // トップページに転送
    }
    protected function handleTwoFactorCheck(Request $request) // 二要素認証のチェック
    {
        $user = Auth::user();
        if ($user && !empty($user->totp_secret)) {
            $request->session()->put('auth.2fa_reqired', true);
            return redirect()->route('2fa.get'); // 二要素認証の入力画面に転送
        }
        return redirect()->intended('/');
    }
}
