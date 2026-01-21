<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Review;
use App\Models\Photo;

class AdminUgcTest extends TestCase
{
    public function test_監視・管理(): void
    {
        $this->get(route('admin.ugc', 0))->assertStatus(200);
        foreach (['review', 'photo'] as $type) {
            $this->post(route('admin.ugc.del'), [
                'type' => $type,
                'id' => 1,
            ])->assertStatus(302);
        }
        $this->assertNull(Review::find(1));
        $this->assertNull(Photo::find(1));
        $this->post(route('admin.ugc.del'), [])->assertStatus(302);
        $this->post(route('admin.ugc.del'), [
            'type' => 'a',
            'id' => 1,
        ])->assertStatus(302);
        $this->post(route('admin.ugc.del'), [
            'type' => 'review',
            'id' => 'a',
        ])->assertStatus(302);
    }
}
