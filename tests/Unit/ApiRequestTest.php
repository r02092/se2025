<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Validator;

class ApiRequestTest extends TestCase
{
    public function test_apiリクエスト(): void
    {
        $request = new ApiRequest();
        $this->assertEquals(
            true,
            Validator::make([], $request->rules())->passes(),
        );
        $this->assertEquals(
            true,
            Validator::make(
                [
                    'from_date' => '2026-01-15',
                ],
                $request->rules(),
            )->passes(),
        );
        $this->assertEquals(
            true,
            Validator::make(
                [
                    'to_date' => '2026-01-15',
                ],
                $request->rules(),
            )->passes(),
        );
        $this->assertEquals(
            true,
            Validator::make(
                [
                    'from_date' => '2026-01-15',
                    'to_date' => '2026-01-15',
                ],
                $request->rules(),
            )->passes(),
        );
        $this->assertEquals(
            false,
            Validator::make(
                [
                    'from_date' => 1,
                ],
                $request->rules(),
            )->passes(),
        );
    }
}
