<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Requests\AiApiRequest;
use Illuminate\Support\Facades\Validator;

class AiApiRequestTest extends TestCase
{
    public function test_スポット推薦リクエスト(): void
    {
        $request = new AiApiRequest();
        $this->assertEquals(
            true,
            Validator::make(
                [
                    'chat' => 'この間にある観光スポットを推薦して',
                    'from' => 2,
                    'to' => 6,
                ],
                $request->rules(),
            )->passes(),
        );
        $this->assertEquals(
            true,
            Validator::make(
                [
                    'chat' => 'この周辺にある観光スポットを推薦して',
                    'from' => 4,
                ],
                $request->rules(),
            )->passes(),
        );
        $this->assertEquals(
            false,
            Validator::make([], $request->rules())->passes(),
        );
        $this->assertEquals(
            false,
            Validator::make(
                [
                    'chat' => 'この間にある観光スポットを推薦して',
                    'from' => 1,
                    'to' => 1,
                ],
                $request->rules(),
            )->passes(),
        );
        $this->assertEquals(
            false,
            Validator::make(
                [
                    'chat' => 'この周辺にある観光スポットを推薦して',
                    'from' => 99,
                ],
                $request->rules(),
            )->passes(),
        );
    }
}
