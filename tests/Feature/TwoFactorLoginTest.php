<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class TwoFactorLoginTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    public function test_設定済みのユーザーはログイン後に2FA画面へ飛ばされる()
    {
        // 1. 通常のログイン処理を行う
        $response = $this->post('/login', [
            'login_name' => '2fa_user',
            'password' => 'password123',
        ]);

        // 2. 2FA入力画面へリダイレクトされることを確認
        $response->assertRedirect();

        // セッションに「2FA認証待ち」フラグが立っているか確認
        $response->assertSessionHas('auth.2fa_required');
    }

    public function test_正しいコードを入力すれば認証を通過できる()
    {
        // 2fa_user を取得
        $user = User::where('login_name', '2fa_user')->first();

        $google2fa = new Google2FA();
        $validSecret = $google2fa->generateSecretKey();

        $user->totp_secret = $validSecret;
        $user->save();

        // ログイン状態にする（ただし2FAは未突破の状態をシミュレート）
        $this->actingAs($user);

        $google2fa = new Google2FA();
        $currentCode = $google2fa->getCurrentOtp($validSecret);

        // 2FAコード送信
        $response = $this->post(route('2fa.verify'), [
            'one_time_password' => $currentCode,
        ]);

        // 通過してホームへリダイレクト
        $response->assertRedirect(route('home'));

        // セッションから2FAフラグが消えている（あるいは認証済みフラグがある）こと
        $response->assertSessionMissing('auth.2fa');
    }
}
