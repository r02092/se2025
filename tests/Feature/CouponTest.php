<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Http\Controllers\SubscriptionController;

class CouponTest extends TestCase
{
    public function test_クーポン画面(): void
    {
        $this->actingAs(User::find(2));
        $response = $this->get(route('coupon'));
        $response->assertStatus(200);
    }
}
