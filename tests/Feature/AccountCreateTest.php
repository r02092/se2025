<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AccountCreateTest extends TestCase
{
    public function test_アカウント作成(): void
    {
        $this->get(route('signup'))->assertStatus(200);
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
        $this->post(route('signup.post'), [])->assertStatus(302);
        $this->post(route('signup.post'), [
            'name' => '',
            'username' => 'testuser',
            'password' => 'testpass',
            'password_confirm' => 'testpass',
        ])->assertStatus(302);
        $this->post(route('signup.post'), [
            'name' => str_repeat('a', 999),
            'username' => 'testuser',
            'password' => 'testpass',
            'password_confirm' => 'testpass',
        ])->assertStatus(302);
        $this->post(route('signup.post'), [
            'name' => 'Test User',
            'username' => '',
            'password' => 'testpass',
            'password_confirm' => 'testpass',
        ])->assertStatus(302);
        $this->post(route('signup.post'), [
            'name' => 'Test User',
            'username' => '!',
            'password' => 'testpass',
            'password_confirm' => 'testpass',
        ])->assertStatus(302);
        $this->post(route('signup.post'), [
            'name' => 'Test User',
            'username' => str_repeat('a', 999),
            'password' => 'testpass',
            'password_confirm' => 'testpass',
        ])->assertStatus(302);
        $this->post(route('signup.post'), [
            'name' => 'Test User',
            'username' => 'testuser',
            'password' => 'test',
            'password_confirm' => 'test',
        ])->assertStatus(302);
    }
}
