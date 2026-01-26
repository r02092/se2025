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

        // 4. チェックを通過
        // 5. 2FAチェック
        if (!empty($user->totp_secret)) {
            session(['login.2fa_user_id' => $user->id]);
            return redirect()->route('2fa.index');
        }

        // 6. 通常ログイン完了
        $this->ensureUserIcon($user);
        Auth::login($user);
        $request->session()->regenerate();
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

        $user = User::where('login_name', $socialUser->getEmail())
            ->orWhere('google_id', $socialUser->getId())
            ->first();

        if ($user) {
            if (empty($user->google_id)) {
                $user->google_id = $socialUser->getId();
                $user->save();
            }
            // アイコンチェック (ログイン前に行う)
            $this->ensureUserIcon($user);
            Auth::login($user);
        } else {
            $user = User::create([
                'name' => $socialUser->getName(),
                'login_name' => $socialUser->getEmail(),
                'google_id' => $socialUser->getId(),
                'password' => password_hash(Str::random(16), PASSWORD_ARGON2ID),
                'provider' => 1,
            ]);
            // 新規作成時もアイコンチェック
            $this->ensureUserIcon($user);
            Auth::login($user);
        }
        session()->regenerate();
        return $this->handlePostLogin($user);
    }

    private function handlePostLogin($user)
    {
        if (!empty($user->totp_secret)) {
            session(['login.2fa_user_id' => $user->id]);
            return redirect()->route('2fa.index');
        }

        Auth::login($user);
        session()->regenerate();
        return redirect()->route('home');
    }

    /**
     * ユーザーのアイコンが未設定の場合、デフォルトアイコンをコピーして設定する
     */
    /**
     * ユーザーのアイコンが未設定、またはファイルが存在しない場合、デフォルトアイコンを復元する
     */
    private function ensureUserIcon(User $user)
    {
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        $defaultIconPath = 'icons/default_icon.jpg';

        // ユーザーの現在のアイコンパスを特定
        $userIconExists = false;
        if (!empty($user->icon_ext)) {
            $userIconPath = 'icons/' . $user->id . '.' . $user->icon_ext;
            $userIconExists = $disk->exists($userIconPath);
        }

        // DB未設定、またはファイル実体がない場合はデフォルトをコピー
        if (empty($user->icon_ext) || !$userIconExists) {
            \Illuminate\Support\Facades\Log::info(
                "Restoring default icon for user {$user->id} (DB ext: {$user->icon_ext}, File exists: " .
                    ($userIconExists ? 'yes' : 'no') .
                    ')',
            );

            if ($disk->exists($defaultIconPath)) {
                try {
                    // Force copy to .jpg extension
                    $disk->copy(
                        $defaultIconPath,
                        'icons/' . $user->id . '.jpg',
                    );

                    // DB update
                    $user->icon_ext = 'jpg';
                    $user->save();
                    \Illuminate\Support\Facades\Log::info(
                        "Default icon restored for user {$user->id}",
                    );
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error(
                        'Failed to restore icon: ' . $e->getMessage(),
                    );
                }
            } else {
                \Illuminate\Support\Facades\Log::error(
                    "Default icon source missing at {$defaultIconPath}",
                );
            }
        }
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
