<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class ProfileTwoFactorTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    public function test_未設定のユーザーは設定画面を開ける()
    {
        $user = User::where('login_name', 'test_user')->first();
        $response = $this->actingAs($user)->get(route('profile.2fa'));
        $response->assertStatus(200);
        $response->assertSee('二要素認証設定');
    }

    public function test_未設定のユーザーは2FAを有効化できる()
    {
        $user = User::where('login_name', 'test_user')->first();
        $this->actingAs($user);
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        $validCode = $google2fa->getCurrentOtp($secret);
        $response = $this->post(route('profile.2fa.store'), [
            'secret_key' => $secret,
            'one_time_password' => $validCode,
        ]);
        $response->assertRedirect(route('profile.2fa'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'totp_secret' => $secret,
        ]);
    }
}
