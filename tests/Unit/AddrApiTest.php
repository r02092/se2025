<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\AddrApiController;

class AddrApiTest extends TestCase
{
    public function test_住所推測(): void
    {
        $controller = new AddrApiController();
        $this->assertEquals($controller->get('0')->original, [
            'city' => 0,
            'addr' => '',
        ]);
        $this->assertEquals($controller->get('0000000')->original, [
            'city' => 0,
            'addr' => '',
        ]);
        $this->assertEquals($controller->get('0295504')->original, [
            'city' => 3366,
            'addr' => '槻沢２',
        ]);
        $this->assertEquals($controller->get('0295512')->original, [
            'city' => 3366,
            'addr' => '川尻４',
        ]);
        $this->assertEquals($controller->get('4536101')->original, [
            'city' => 23105,
            'addr' => '',
        ]);
        $this->assertEquals($controller->get('4536190')->original, [
            'city' => 23105,
            'addr' => '',
        ]);
        $this->assertEquals($controller->get('4980000')->original, [
            'city' => 0,
            'addr' => '',
        ]);
        $this->assertEquals($controller->get('7614101')->original, [
            'city' => 37322,
            'addr' => '',
        ]);
        $this->assertEquals($controller->get('7660001')->original, [
            'city' => 37403,
            'addr' => '',
        ]);
        $this->assertEquals($controller->get('7660002')->original, [
            'city' => 37403,
            'addr' => '',
        ]);
        $this->assertEquals($controller->get('7812100')->original, [
            'city' => 39000,
            'addr' => '',
        ]);
        $this->assertEquals($controller->get('7812110')->original, [
            'city' => 39386,
            'addr' => '',
        ]);
        $this->assertEquals($controller->get('7816410')->original, [
            'city' => 39303,
            'addr' => '',
        ]);
        $this->assertEquals($controller->get('7820000')->original, [
            'city' => 39212,
            'addr' => '',
        ]);
        $this->assertEquals($controller->get('7820003')->original, [
            'city' => 39212,
            'addr' => '土佐山田町宮ノ口',
        ]);
        $this->assertEquals($controller->get('7820077')->original, [
            'city' => 39212,
            'addr' => '土佐山田町佐野',
        ]);
        $this->assertEquals($controller->get('7828502')->original, [
            'city' => 39212,
            'addr' => '土佐山田町宮ノ口１８５',
        ]);
        $this->assertEquals($controller->get('7871107')->original, [
            'city' => 39210,
            'addr' => '',
        ]);
        $this->assertEquals($controller->get('8572427')->original, [
            'city' => 42212,
            'addr' => '大島町',
        ]);
    }
}
