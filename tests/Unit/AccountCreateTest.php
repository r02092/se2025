<?php

namespace Tests\Unit;

use App\Http\Controllers\AccountCreateController;
use App\Http\Requests\AccountCreateRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_アカウント作成(): void
    {
        // コントローラーのテスト
        // リクエストデータの作成
        $request = new AccountCreateRequest();
        $request->merge([
            'name' => 'Test User',
            'username' => 'testuser',
            'password' => 'testpass',
            'password_confirmation' => 'testpass', // 修正：項目名変更
        ]);

        // コントローラー実行
        $controller = new AccountCreateController();
        $response = $controller->post($request);

        // 修正：以前は400エラーなどを確認していたかもしれませんが、
        // 現在は成功してリダイレクト(302)されるのが正解です。
        $this->assertEquals(302, $response->getStatusCode());

        // ユーザーが作成されたか確認
        $this->assertNotNull(User::where('login_name', 'testuser')->first());
    }
}