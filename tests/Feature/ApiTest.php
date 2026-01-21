<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiTest extends TestCase
{
    public function test_api(): void
    {
        $this->get(route('api'), [
            'Authorization' =>
                'Bearer R9AnJFbw34/CWEQhwBVzDC4BGiAfM6IigWoU5pKm9L7y3zh6mzKEB32KjcyhPdq.d6hNo6+Iykh0EOVHQ/Wyp',
        ])->assertStatus(200);
        $this->get(
            route('api', [
                'from_date' => '2026-01-15',
            ]),
            [
                'Authorization' =>
                    'Bearer R9AnJFbw34/CWEQhwBVzDC4BGiAfM6IigWoU5pKm9L7y3zh6mzKEB32KjcyhPdq.d6hNo6+Iykh0EOVHQ/Wyp',
            ],
        )->assertStatus(200);
        $this->get(
            route('api', [
                'to_date' => '2026-01-15',
            ]),
            [
                'Authorization' =>
                    'Bearer R9AnJFbw34/CWEQhwBVzDC4BGiAfM6IigWoU5pKm9L7y3zh6mzKEB32KjcyhPdq.d6hNo6+Iykh0EOVHQ/Wyp',
            ],
        )->assertStatus(200);
        $this->get(
            route('api', [
                'from_date' => '2026-01-15',
                'to_date' => '2026-01-15',
            ]),
            [
                'Authorization' =>
                    'Bearer R9AnJFbw34/CWEQhwBVzDC4BGiAfM6IigWoU5pKm9L7y3zh6mzKEB32KjcyhPdq.d6hNo6+Iykh0EOVHQ/Wyp',
            ],
        )->assertStatus(200);
        $this->get(
            route('api', [
                'from_date' => 1,
            ]),
            [
                'Authorization' =>
                    'Bearer R9AnJFbw34/CWEQhwBVzDC4BGiAfM6IigWoU5pKm9L7y3zh6mzKEB32KjcyhPdq.d6hNo6+Iykh0EOVHQ/Wyp',
            ],
        )->assertStatus(302);
        $this->get(route('api'))->assertStatus(401);
        $this->get(route('api'), [
            'Authorization' => 'Bearer invalid_api_key',
        ])->assertStatus(401);
    }
}
