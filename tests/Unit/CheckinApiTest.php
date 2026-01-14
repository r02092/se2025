<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Spot;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckinApiTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;
    // 1.スポット周辺でチェックインができるか
    public function test_checkin_success_with_valid_data(): void
    {
        $user = User::find(6);
        $spot = Spot::find(2);

        $response = $this->actingAs($user)->postJson('/api/checkin', [
            'stamp_key' => $spot->stamp_key,
            'lat' => 33.504458, // スポットと全く同じ位置
            'lng' => 133.906497,
        ]);

        $response->assertStatus(200)->assertJson([
            'message' => 'チェックインに成功しました。',
            'spot_name' => 'ごめん・なはり線',
            'coupon_result' => [
                'success' => true,
            ],
        ]);

        // スタンプが記録されたか
        $this->assertDatabaseHas('stamps', [
            'user_id' => 6,
            'spot_id' => 2,
        ]);
    }

    // 2.距離が遠すぎるときにエラーになるか
    public function test_checkin_fail_if_too_far(): void
    {
        $user = User::find(6);
        $spot = Spot::find(2); // ごめん・なはり線

        // スポットから遠く離れた場所 (例: 東京)
        $farLat = 35.6895;
        $farLng = 139.6917;

        $response = $this->actingAs($user)->postJson('/api/checkin', [
            'stamp_key' => $spot->stamp_key,
            'lat' => $farLat,
            'lng' => $farLng,
        ]);

        $response->assertStatus(400)->assertJsonFragment([
            'error' =>
                'スポットから離れすぎています。現地に近づいて再度お試しください。',
        ]);

        // スタンプが記録されていないこと
        $this->assertDatabaseMissing('stamps', [
            'user_id' => 6,
            'spot_id' => 2,
        ]);
    }

    // 3.不正なスタンプキーのときにエラーになるか
    public function test_checkin_fail_invalid_key(): void
    {
        $user = User::find(6);

        $response = $this->actingAs($user)->postJson('/api/checkin', [
            'stamp_key' => 999999999, // 存在しないキー
            'lat' => 33.504458,
            'lng' => 133.906497,
        ]);

        $response->assertStatus(404)->assertJson([
            'error' => '該当するスポットが見つかりません。',
        ]);
    }

    // 4.バリデーションエラー
    public function test_checkin_validation_error(): void
    {
        $user = User::find(6);

        $response = $this->actingAs($user)->postJson('/api/checkin', [
            // 空のデータ
        ]);

        $response->assertStatus(400)->assertJsonStructure([
            'error',
            'details' => ['stamp_key', 'lat', 'lng'], // エラー詳細に含まれるキー
        ]);
    }
}
