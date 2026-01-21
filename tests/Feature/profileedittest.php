<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class profileedittest extends TestCase
{
    use RefreshDatabase; // テストごとにデータベースをリセット

    /**
     * プロフィール更新のテスト
     */
    public function test_profile_can_be_updated(): void
    {
        // 1. テスト用の利用者を作成してログイン状態にする
        $user = User::factory()->create([
            'name' => '既存の名前',
            'login_name' => 'old_login_id',
        ]);

        // 2. 変更後のデータを準備
        $updatedData = [
            'name' => '新しい名前',
            'login_name' => 'new_login_id',
        ];

        // 3. プロフィール更新URLにPOSTリクエストを送信 (MC09を呼び出し)
        $response = $this->actingAs($user)
                         ->post(route('profile.update'), $updatedData);

        // 4. 検証：リダイレクトが成功しているか
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('profile.edit'));

        // 5. 検証：データベースの中身が書き換わっているか
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => '新しい名前',
            'login_name' => 'new_login_id',
        ]);
    }

    /**
     * 他人のログイン名と重複した場合のテスト
     */
    public function test_profile_update_fails_if_login_name_is_taken(): void
    {
        // 既存のユーザーAとBを作成
        $userA = User::factory()->create(['login_name' => 'user_a']);
        $userB = User::factory()->create(['login_name' => 'user_b']);

        // ユーザーAとしてログインし、Bのログイン名に変更しようとする
        $response = $this->actingAs($userA)
                         ->post(route('profile.update'), [
                             'name' => 'ユーザーA名前変更',
                             'login_name' => 'user_b', // 重複
                         ]);

        // エラーがあることを確認
        $response->assertSessionHasErrors('login_name');
        
        // データベースが書き換わっていないことを確認
        $this->assertDatabaseMissing('users', [
            'id' => $userA->id,
            'name' => 'ユーザーA名前変更',
        ]);
    }
}