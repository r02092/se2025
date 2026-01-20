<?php

namespace Tests\Unit;

use Tests\TestCase;

class DetailControllerTest extends TestCase
{
    public function test_create_screen_param_not_null(): void
    {
        $response = $this->get('/detail?id=1');

        // ステータスコードが正常か
        $response->assertStatus(200);
        // 正しいBladeファイルを使っているか
        $response->assertViewIs('detail');
        // Viewにpostsという変数が渡されているか
        $response->assertViewHas('spot');
    }

    public function test_create_screen_param_null(): void
    {
        $response = $this->get('/detail');

        // ステータスコードがNot Foundか
        $response->assertStatus(404);
    }
}
