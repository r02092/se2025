<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Requests\AdminUgcRequest;
use Illuminate\Support\Facades\Validator;

class AdminUgcRequestTest extends TestCase
{
    public function test_監視・管理リクエスト(): void
    {
        $request = new AdminUgcRequest();
        $this->assertEquals(
            true,
            Validator::make(
                [
                    'type' => 'review',
                    'id' => 1,
                ],
                $request->rules(),
            )->passes(),
        );
        $this->assertEquals(
            true,
            Validator::make(
                [
                    'type' => 'photo',
                    'id' => 1,
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
                    'type' => 'a',
                    'id' => 1,
                ],
                $request->rules(),
            )->passes(),
        );
        $this->assertEquals(
            false,
            Validator::make(
                [
                    'type' => 'review',
                    'id' => 'a',
                ],
                $request->rules(),
            )->passes(),
        );
    }
}
