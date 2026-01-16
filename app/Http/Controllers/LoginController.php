<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

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

    // Google 認証ページへリダイレクト
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'login_name' => 'Googleログインに失敗しました。',
                ]);
        }

        $user = User::where('email', $soclialUser->getEmail())
            ->orWhere('google_ud', $socialUser->getId())
            ->first();

        if ($user) {
            if (empty($user->google_id)) {
                $user->google_id = $socialUser->getId();
                $user->save();
            }
            Auth::login($user);
        } else {
            $user = USer::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'login_name' => $socialUser->getEmail(),
                'google_id' => $socialUser->getId(),
                'password' => password_hash(Str::radom(16), PASSWORD_ARGON2ID),
                'provider' => 1,
            ]);
            Auth::login($user);
        }
        session()->regenerate();
        return $this->handlePostLogin($user);
    }

    private function handlePostLogin($user)
    {
        if (!empty($user->totp_secret)) {
            session(['auth.2fa_required' => true]);
            return redirect()->route('2fa.index');
        }

        return redirect()->route('home');
    }

    // ログアウト確認ページ表示
    public function showLogoutForm()
    {
        return view('logout');
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
