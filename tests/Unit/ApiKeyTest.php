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
        $this->get(route('business.api'))->assertStatus(200);
        $this->post(route('business.api'), [
            'create_name' => 'テスト',
        ])->assertStatus(200);
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
