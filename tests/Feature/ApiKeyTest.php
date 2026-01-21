<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class ApiKeyTest extends TestCase
{
    public function test_キー発行(): void
    {
        $this->actingAs(User::find(3));
        $this->get(route('business.api'))->assertStatus(200);
        $this->post(route('business.api'), [
            'create_name' => 'テスト',
        ])->assertStatus(200);
        $this->post(route('business.api'), [
            'delete_id' => 2,
        ])->assertStatus(302);
    }
}
