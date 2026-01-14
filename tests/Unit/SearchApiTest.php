<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\SearchApiController;

class SearchApiTest extends TestCase
{
    public function test_get_spot_list_valid_input(): void
    {
        $response = $this->getJson('/filtering', [
            'type' => 0,
            'keyword' => 'パンどろぼう',
        ]);

        $response->assertStatus(200)->assertJson([
            [
                'user_id' => 1,
                'plan' => 1,
                'type' => 0,
                'name' => 'chimney',
            ],
        ]);
    }

    public function test_get_spot_list_null_input(): void
    {
        $response = $this->getJson('/filtering');

        $response->assertStatus(200)->assertJson([
            [
                'user_id' => 1,
                'plan' => 1,
                'type' => 0,
                'name' => 'chimney',
            ],
        ]);
    }
}
