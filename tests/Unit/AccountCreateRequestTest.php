<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Requests\AccountCreateRequest;
use Illuminate\Support\Facades\Validator;

class AccountCreateRequestTest extends TestCase
{
    public function test_アカウント作成リクエスト(): void
    {
        $request = new AccountCreateRequest();

        // 1. 正常系：すべて正しい入力
        $this->assertEquals(
            true,
            Validator::make(
                [
                    'name' => 'Test User',
                    'username' => 'testuser',
                    'password' => 'testpass',
                    'password_confirmation' => 'testpass', // 修正箇所
                ],
                $request->rules(),
            )->passes(),
        );

        // 2. 異常系：空配列
        $this->assertEquals(
            false,
            Validator::make([], $request->rules())->passes(),
        );

        // 3. 異常系：名前が空
        $this->assertEquals(
            false,
            Validator::make(
                [
                    'name' => '',
                    'username' => 'testuser',
                    'password' => 'testpass',
                    'password_confirmation' => 'testpass', // 修正箇所
                ],
                $request->rules(),
            )->passes(),
        );

        // 4. 異常系：名前が長すぎる
        $this->assertEquals(
            false,
            Validator::make(
                [
                    'name' => str_repeat('a', 999),
                    'username' => 'testuser',
                    'password' => 'testpass',
                    'password_confirmation' => 'testpass', // 修正箇所
                ],
                $request->rules(),
            )->passes(),
        );

        // 5. 異常系：ユーザー名が空
        $this->assertEquals(
            false,
            Validator::make(
                [
                    'name' => 'Test User',
                    'username' => '',
                    'password' => 'testpass',
                    'password_confirmation' => 'testpass', // 修正箇所
                ],
                $request->rules(),
            )->passes(),
        );

        // 6. 異常系：ユーザー名に禁止文字が含まれる
        $this->assertEquals(
            false,
            Validator::make(
                [
                    'name' => 'Test User',
                    'username' => '!',
                    'password' => 'testpass',
                    'password_confirmation' => 'testpass', // 修正箇所
                ],
                $request->rules(),
            )->passes(),
        );

        // 7. 異常系：ユーザー名が長すぎる
        $this->assertEquals(
            false,
            Validator::make(
                [
                    'name' => 'Test User',
                    'username' => str_repeat('a', 999),
                    'password' => 'testpass',
                    'password_confirmation' => 'testpass', // 修正箇所
                ],
                $request->rules(),
            )->passes(),
        );

        // 8. 異常系：パスワードが短すぎる
        $this->assertEquals(
            false,
            Validator::make(
                [
                    'name' => 'Test User',
                    'username' => 'testuser',
                    'password' => 'test',
                    'password_confirmation' => 'test', // 修正箇所
                ],
                $request->rules(),
            )->passes(),
        );
    }
}
