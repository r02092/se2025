<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Http\Controllers\ApiKeyController;
use App\Http\Requests\ApiKeyRequest;

class ApiKeyTest extends TestCase
{
    public function test_発行(): void
    {
        $this->actingAs(User::find(3));
        $response = $this->get(route('business.api'));
        $response->assertStatus(200);
        $response = $this->post(route('business.api'), [
            'create_name' => 'テスト',
        ]);
        $response->assertStatus(200);
        $this->assertEquals(
            302,
            (new ApiKeyController())
                ->post(
                    new ApiKeyRequest([
                        'delete_id' => 2,
                    ]),
                )
                ->getStatusCode(),
        );
    }
}
