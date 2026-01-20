<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class InsertUserTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    public function test_一般ユーザーは利用者追加画面にアクセスできない()
    {
        $user = User::where('login_name', 'test_user')->first();
        $response = $this->actingAs($user)->get(route('admin.users.create'));
        $response ->assertForbidden();
    }


    public function test_管理者は利用者追加画面を表示できる()
    {
        $admin = User::where('login_name', 'share_admin')->first();
        $response = $this->actingAs($admin)->get(route('admin.users.create'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.create');
    }

    public function test_管理者は新規ユーザーを追加できる()
    {
        $admin = User::where('login_name', 'share_admin')->first();
        $postData = [
            'login_name' => 'new_stuff_001',
            'password' => 'password_new',
            'name' => '新人スタッフ',
            'permission' => 1,
        ];

        $response = $this->actingAs($admin)->post(route('admin.users.store'), $postData);
        $response->assertRedirect(route('admin.users.list'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'login_name' => 'new_stuff_001',
            'name' => '新人スタッフ',
            'permission' => 1,
        ]);
    }
}
