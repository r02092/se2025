<?php

namespace Tests\Feature;

use Tests\TestCase;

class AiApiTest extends TestCase
{
    public function test_スポット推薦(): void
    {
        $this->post(route('ai'), [
            'chat' => 'この間にある観光スポットを推薦して',
            'from' => 2,
            'to' => 6,
        ])->assertStatus(200);
        $this->post(route('ai'), [
            'chat' => 'この周辺にある観光スポットを推薦して',
            'from' => 47,
        ])->assertStatus(400);
        $this->post(route('ai'), [
            'chat' => 'この周辺にある観光スポットを推薦して',
            'from' => 4,
        ])->assertStatus(200);
        $this->post(route('ai'), [])->assertStatus(302);
        $this->post(route('ai'), [
            'chat' => 'この間にある観光スポットを推薦して',
            'from' => 1,
            'to' => 1,
        ])->assertStatus(302);
        $this->post(route('ai'), [
            'chat' => 'この周辺にある観光スポットを推薦して',
            'from' => 99,
        ])->assertStatus(302);
    }
}
