<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AccountCreateTest extends TestCase
{
    public function test_アカウント作成(): void
    {
        Auth::logout();
        $this->post(route('signup.post'), [
            'name' => 'Test User',
            'username' => 'testuser',
            'password' => 'testpass',
            'password_confirm' => 'testpass',
        ])->assertRedirect();
        $this->assertNotNull(User::where('login_name', 'testuser')->first());
        Auth::logout();
        $this->post(route('signup.post'), [
            'name' => 'Test User 2',
            'username' => 'testuser2',
            'password' => 'testpass',
            'password_confirm' => 'testpassword',
        ])->assertStatus(400);
        $this->assertNull(User::where('login_name', 'testuser2')->first());
    }
}
