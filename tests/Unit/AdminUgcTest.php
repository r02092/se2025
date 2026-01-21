<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\AdminUgcController;
use App\Http\Requests\AdminUgcRequest;
use App\Models\Review;
use App\Models\Photo;

class AdminUgcTest extends TestCase
{
    public function test_監視・管理(): void
    {
        $controller = new AdminUgcController();
        $this->get(route('admin.ugc', 0))->assertStatus(200);
        foreach (['review', 'photo'] as $type) {
            $this->assertEquals(
                302,
                $controller
                    ->post(
                        new AdminUgcRequest([
                            'type' => $type,
                            'id' => 1,
                        ]),
                    )
                    ->getStatusCode(),
            );
        }
        $this->assertNull(Review::find(1));
        $this->assertNull(Photo::find(1));
    }
}
