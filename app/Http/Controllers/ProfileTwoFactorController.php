<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PragmaRX\Google2FAQRCode\Google2FA;

class ProfileTwoFactorController extends Controller
{
    /**
     * 2FA設定画面の表示
     * (未設定ならQRコードを表示、設定済みなら解除ボタンを表示)
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // 既に設定済みの場合
        if (!empty($user->totp_secret)) {
            return view('profile-2fa', [
                'enabled' => true,
            ]);
        }

        $google2fa = new Google2FA();
        
        $secretKey = $google2fa->generateSecretKey();

        $qrImage = $google2fa->getQRCodeInline(
            config('app.name', 'SceneTrip'),
            $user->login_name,
            $secretKey
        );

        return view('profile-2fa', [
            'enabled' => false,
            'secretKey' => $secretKey,
            'qrImage' => $qrImage,
        ]);
    }

    
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'secret_key' => ['required', 'string'],
            'one_time_password' => ['required', 'digits:6'],
        ]);

        $google2fa = new Google2FA();
        $secret = $request->input('secret_key');
        $code = $request->input('one_time_password');

        // 入力されたコードが、生成した秘密鍵と合致するか検証
        if ($google2fa->verifyKey($secret, $code)) {
            // 成功したらDBに保存
            $user = Auth::user();
            $user->totp_secret = $secret;
            $user->save();

            return redirect()->route('profile.2fa')
                ->with('success', '二要素認証を有効にしました。');
        }

        return back()->withErrors(['one_time_password' => '認証コードが正しくありません。']);
    }

    /**
     * 2FAの無効化（設定解除）
     */
    public function destroy(): RedirectResponse
    {
        $user = Auth::user();
        $user->totp_secret = null; // 秘密鍵を削除
        $user->save();

        return redirect()->route('profile.2fa')
            ->with('success', '二要素認証を解除しました。');
    }
}