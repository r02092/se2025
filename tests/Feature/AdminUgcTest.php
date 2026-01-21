<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Review;
use App\Models\Photo;

class AdminUgcTest extends TestCase
{
    public function test_監視・管理(): void
    {
        $response = $this->get(route('admin.ugc', 0));
        $response->assertStatus(200);
        foreach (['review', 'photo'] as $type) {
            $this->post(route('admin.ugc.del'), [
                'type' => $type,
                'id' => 1,
            ])->assertStatus(302);
        }
        $this->assertNull(Review::find(1));
        $this->assertNull(Photo::find(1));
    }
}
