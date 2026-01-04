<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Lalavel\Socialite\Facades\Socialite;
use Mockery;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;
    // ログイン画面が正しく表示されるか
    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertView('login');
    }
    // Scenetripアカウントで正常にログインできるか
    public function test_users_can_authenticate_scenetrip_account()
    {
        $user = User::factory()->create([
            'login_name' => 'test_user',
            'password' => 'password123',
            'provider' => User::PROVIDER_SCENETRIP,
            'totp_secret' =>null,
            'permission' => User::PERMISSION_USER,
            'name' =>  'Test User',
            'icon_ext' => 'png',
            'num_plan_std' => 0,
            'num_plan_prm' => 0,
        ]);
        $response = $this->post(route('login'), [
            'login_name' => 'test_user',
            'password' => 'password123',
        ]);
        $this->assertAuthenticatedAs($user);
        $response->aassertRedirect('/');
    }
    // パスワードが間違っている場合にログインできないか
    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create([
            'login_name' => 'test_user',
            'password' => 'password123',
            'provider' => User::PROVIDER_SCENETRIP,
            'permission' => 1,
            'name' =>  'test',
            'icon_ext' => 'png',
            'num_plan_std' => 0,
            'num_plan_prm' => 0,
        ]);
        $this->form(route('login'))->post(route('login'),[
            'login_name' => 'test_user',
            'password' => 'wrongpassword',
        ]);
        $this->assertGuest();
        $this->assertSessionHasErrors('login_name');
    }
    // 二要素認証設定済みユーザは2FA画面にリダイレクトされるか
    public function test_redirect_to_2fa_page_if_totp_secret_exists()
    {
        $user = User::factory()->create([
            'login_name' => 'test_user',
            'password' => 'password123',
            'provider' => User::PROVIDER_SCENETRIP,
            'totp_secret' => 'binary_secret_data',
            'permission' => 1,
            'name' =>  'test',
            'icon_ext' => 'png',
            'num_plan_std' => 0,
            'num_plan_prm' => 0,
        ]);
        $response = $this->post(route('login'),[
            'login_name' => 'test_user',
            'password' => 'password123',
        ]);
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('auth.2fa_required'));
        $response->assertRedirect(route('2fa.index'));
    }
    // Googleアカウントでの新規登録とログイン正常にされるか
    public function test_google_callback_creates_user_and_logs_in()
    {
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getID')->andReturn('google_id_123');
        $abstractUser->shouldReceive('getName')->andReturn('Google User');
        $abstractUser->shouldReceive('getEmail')->andReturn('test@example.com');
        $abstractUser->shouldReceive('getAvatar')->andReturn('http://example.com/avatar.jpg');
        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);
        Socialite::shouldReceive('deiver')->with('google')->andReturn($provider);
        $response = $this->get('/login/google/callback');
        $this->assertDatabaseHas('users',[
            'provider' => User::PROVIDER_GOOGLE,
            'login_name' => 'google_id_123',
            'name' => 'Google User',
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }
    // ログアウトが正常に動作するか
    public function test_user_can_logout()
    {
        $user = User::factory()->create([
            'permission' => 1,
            'name' =>  'test',
            'icon_ext' => 'png',
            'num_plan_std' => 0,
            'num_plan_prm' => 0,
            'login_name' => 'test',
            'provider' => 0,
        ]);
        $this->actingAs($user);
        $response = $this->post(route('logout'));
        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
