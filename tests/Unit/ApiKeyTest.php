<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\ApiKeyController;
use App\Http\Requests\ApiKeyRequest;
use App\Models\User;

class ApiKeyTest extends TestCase
{
    public function test_発行(): void
    {
        $controller = new ApiKeyController();
        $this->assertEquals(200, $controller->get()->getStatusCode());
        $this->assertEquals(
            200,
            $controller
                ->post(
                    new ApiKeyRequest([
                        'create_name' => 'テスト',
                    ]),
                )
                ->getStatusCode(),
        );
        $this->assertEquals(
            200,
            $controller
                ->post(
                    new ApiKeyRequest([
                        'delete_id' => 2,
                    ]),
                )
                ->getStatusCode(),
        );
        $this->assertEquals(
            404,
            $controller
                ->post(
                    new ApiKeyRequest([
                        'delete_id' => 9,
                    ]),
                )
                ->getStatusCode(),
        );
        $this->assertEquals(
            400,
            $controller->post(new ApiKeyRequest([]))->getStatusCode(),
        );
    }
}
