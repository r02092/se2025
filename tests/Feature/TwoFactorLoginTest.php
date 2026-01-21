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

        // セッションに「2FA認証用ユーザーID」が入っているか確認
        $response->assertSessionHas('login.2fa_user_id');
        // まだ認証されていないこと
        $this->assertGuest();
    }

    public function test_正しいコードを入力すれば認証を通過できる()
    {
        // 2fa_user を取得
        $user = User::where('login_name', '2fa_user')->first();

        $google2fa = new Google2FA();
        $validSecret = $google2fa->generateSecretKey();

        $user->totp_secret = $validSecret;
        $user->save();

        // ログイン試行してセッションを作る（actingAsではなく、実際のフローを経由するか、セッションをセットする）
        // ここではセッションを直接セットして「パスワード認証通過後」の状態を作る
        $response = $this->withSession(['login.2fa_user_id' => $user->id])
            ->post(route('2fa.verify'), [
                'one_time_password' => $google2fa->getCurrentOtp($validSecret),
            ]);

        // 通過してホームへリダイレクト
        $response->assertRedirect(route('home'));

        // ログイン状態になっていること
        $this->assertAuthenticatedAs($user);
        // セッションから一時IDが消えていること
        $response->assertSessionMissing('login.2fa_user_id');
    }
}
