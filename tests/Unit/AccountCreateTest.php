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
        $this->assertEquals(
            302,
            (new AccountCreateController())
                ->post(
                    new AccountCreateRequest([
                        'name' => 'Test User',
                        'username' => 'testuser',
                        'password' => 'testpass',
                        'password_confirm' => 'testpass',
                    ]),
                )
                ->getStatusCode(),
        );
        $this->assertNotNull(User::where('login_name', 'testuser')->first());
        $this->assertEquals(
            400,
            (new AccountCreateController())
                ->post(
                    new AccountCreateRequest([
                        'name' => 'Test User 2',
                        'username' => 'testuser2',
                        'password' => 'testpass',
                        'password_confirm' => 'testpassword',
                    ]),
                )
                ->getStatusCode(),
        );
    }
}
