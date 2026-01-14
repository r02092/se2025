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
        $this->assertEquals(
            true,
            Validator::make(
                [
                    'name' => 'Test User',
                    'username' => 'testuser',
                    'password' => 'testpass',
                    'password_confirm' => 'testpass',
                ],
                $request->rules(),
            )->passes(),
        );
        $this->assertEquals(
            false,
            Validator::make([], $request->rules())->passes(),
        );
        $this->assertEquals(
            false,
            Validator::make(
                [
                    'name' => '',
                    'username' => 'testuser',
                    'password' => 'testpass',
                    'password_confirm' => 'testpass',
                ],
                $request->rules(),
            )->passes(),
        );
        $this->assertEquals(
            false,
            Validator::make(
                [
                    'name' => str_repeat('a', 999),
                    'username' => 'testuser',
                    'password' => 'testpass',
                    'password_confirm' => 'testpass',
                ],
                $request->rules(),
            )->passes(),
        );
        $this->assertEquals(
            false,
            Validator::make(
                [
                    'name' => 'Test User',
                    'username' => '',
                    'password' => 'testpass',
                    'password_confirm' => 'testpass',
                ],
                $request->rules(),
            )->passes(),
        );
        $this->assertEquals(
            false,
            Validator::make(
                [
                    'name' => 'Test User',
                    'username' => '!',
                    'password' => 'testpass',
                    'password_confirm' => 'testpass',
                ],
                $request->rules(),
            )->passes(),
        );
        $this->assertEquals(
            false,
            Validator::make(
                [
                    'name' => 'Test User',
                    'username' => str_repeat('a', 999),
                    'password' => 'testpass',
                    'password_confirm' => 'testpass',
                ],
                $request->rules(),
            )->passes(),
        );
        $this->assertEquals(
            false,
            Validator::make(
                [
                    'name' => 'Test User',
                    'username' => 'testuser',
                    'password' => 'test',
                    'password_confirm' => 'test',
                ],
                $request->rules(),
            )->passes(),
        );
    }
}
