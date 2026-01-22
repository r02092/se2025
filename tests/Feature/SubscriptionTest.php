<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class SubscriptionTest extends TestCase
{
    public function test_事業者申込(): void
    {
        $this->actingAs(User::find(2));
        $response = $this->get(route('subscription.form'));
        $response->assertStatus(200);
        $response = $this->post(route('subscription.store'), [
            'post_code' => 7820003,
            'city' => 39212,
            'address' => '土佐山田町宮ノ口185',
            'num_plan_std' => 2,
            'num_plan_prm' => 1,
        ]);
        $this->assertDatabaseHas('users', [
            'id' => 2,
            'postal_code' => 7820003,
            'addr_city' => 39212,
            'addr_detail' => '土佐山田町宮ノ口185',
            'num_plan_std' => 2,
            'num_plan_prm' => 1,
            'permission' => 2,
        ]);
        $response = $this->get(route('subscription.confirm'));
        $response->assertStatus(200);
    }
}
