<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\AccountCreateController;
use App\Http\Requests\AccountCreateRequest;
use App\Models\User;

class AccountCreateTest extends TestCase
{
    public function test_アカウント作成(): void
    {
        $controller = new AccountCreateController();
        $response = $controller->post(
            new AccountCreateRequest([
                'name' => 'Test User',
                'login_name' => 'testuser',
                'password' => 'testpass',
                'password_confirm' => 'testpass',
            ]),
        );
        $user = User::where('login_name', 'testuser')->first();
        $this->assertNotNull($user);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
