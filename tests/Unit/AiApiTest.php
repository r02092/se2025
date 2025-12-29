<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\AiApiController;
use App\Http\Requests\AiApiRequest;
use App\Models\User;

class AiApiTest extends TestCase
{
    public function test_スポット推薦(): void
    {
        $controller = new AiApiController();
        $response = $controller->post(
            new AiApiRequest([
                'chat' => 'この間にある観光スポットを推薦して',
                'from' => 2,
                'to' => 6,
            ]),
        );
        echo $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
    }
}
