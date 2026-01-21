<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Requests\ApiKeyRequest;
use Illuminate\Support\Facades\Validator;

class ApiKeyRequestTest extends TestCase
{
    public function test_キー発行リクエスト(): void
    {
        $request = new ApiKeyRequest();
        $this->assertEquals(
            true,
            Validator::make(
                [
                    'create_name' => 'テスト',
                ],
                $request->rules(),
            )->passes(),
        );
        $this->assertEquals(
            true,
            Validator::make(
                [
                    'delete_id' => 1,
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
                    'delete_id' => 9,
                ],
                $request->rules(),
            )->passes(),
        );
    }
}
