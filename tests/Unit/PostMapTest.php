<?php

namespace Tests\Unit;

use Tests\TestCase;

class PostMapTest extends TestCase
{
    public function test_create_screen(): void
    {
        $response = $this->get('/post');

        // ステータスコードが正常か
        $response->assertStatus(200);
        // 正しいBladeファイルを使っているか
        $response->assertViewIs('post');
        // Viewにpostsという変数が渡されているか
        $response->assertViewHas('posts');
    }
}
