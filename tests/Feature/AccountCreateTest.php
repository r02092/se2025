<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AccountCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_アカウント作成(): void
    {
        Auth::logout();
        // 1回目の作成
        $this->post(route('signup.post'), [
            'name' => 'Test User',
            'username' => 'testuser',
            'password' => 'testpass',
            'password_confirmation' => 'testpass', // 修正：項目名変更
        ])->assertRedirect('/'); // 成功時はルートへリダイレクト

        $this->assertNotNull(User::where('login_name', 'testuser')->first());

        Auth::logout();

        // 2回目の作成（ユーザー名の重複チェックなど）
        $this->post(route('signup.post'), [
            'name' => 'Test User 2',
            'username' => 'testuser2',
            'password' => 'testpass2',
            'password_confirmation' => 'testpass2', // 修正：項目名変更
        ])->assertRedirect('/');

        $this->assertNotNull(User::where('login_name', 'testuser2')->first());
    }
}
