<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Hash;
#use Lalavel\Socialite\Facades\Socialite;
#use Mockery;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;
    /* ログイン画面が表示されるか */
    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }
    /* Seederで作った一般ユーザーでログインできるか */
    public function test_users_can_authenticate_using_seeded_user()
    {
        Auth::logout();
        $response = $this->post(route('login'), [
            'login_name' => 'test_user',
            'password' => 'password123',
        ]);
        $this->assertAuthenticated();
        $this->assertAuthenticatedAs(
            User::where('login_name', 'test_user')->firstOrFail(),
        );
        $response->assertRedirect(route('home'));
    }
    /* パスワードが間違っているときのテスト */
    public function test_users_can_not_authenticate_with_invalid_password()
    {
        Auth::logout();
        $response = $this->from(route('login'))->post(route('login'), [
            'login_name' => 'test_user',
            'password' => 'wrong-password',
        ]);
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('login_name');
        $this->assertGuest();
    }
    /* Seederで作った「2FA設定済みユーザー」は2FA画面へリダイレクトされるか */
    public function test_redirects_to_2fa_page_if_seeded_totp_user_logs_in()
    {
        Auth::logout();
        $response = $this->post(route('login'), [
            'login_name' => '2fa_user',
            'password' => 'password123',
        ]);
        $this->assertAuthenticated();
        $this->assertTrue(session('auth.2fa_required'));
        $response->assertRedirect(route('2fa.index'));
    }
}