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
        $response->assertViewIs('photo');
    }

    public function test_load_posts(): void
    {
        $response = $this->get(
            '/post/load?sw_lat=30.0&sw_lng=130.0&ne_lat=40.0&ne_lng=135.0',
        );

        $response->assertStatus(200);
    }
}
