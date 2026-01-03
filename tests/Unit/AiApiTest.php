<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\AiApiController;
use App\Http\Requests\AiApiRequest;

class AiApiTest extends TestCase
{
    public function test_スポット推薦(): void
    {
        $controller = new AiApiController();
        $this->assertEquals(
            200,
            $controller
                ->post(
                    new AiApiRequest([
                        'chat' => 'この間にある観光スポットを推薦して',
                        'from' => 2,
                        'to' => 6,
                    ]),
                )
                ->getStatusCode(),
        );
        $this->assertEquals(
            400,
            $controller
                ->post(
                    new AiApiRequest([
                        'chat' => 'この周辺にある観光スポットを推薦して',
                        'from' => 2,
                    ]),
                )
                ->getStatusCode(),
        );
    }
}
