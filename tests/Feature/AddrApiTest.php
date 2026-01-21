<?php

namespace Tests\Feature;

use Tests\TestCase;

class AddrApiTest extends TestCase
{
    public function test_住所推測(): void
    {
        $this->get('/addr/0')
            ->assertStatus(200)
            ->assertJson([
                'city' => 0,
                'addr' => '',
            ]);
        $this->get('/addr/0000000')
            ->assertStatus(200)
            ->assertJson([
                'city' => 0,
                'addr' => '',
            ]);
        $this->get('/addr/0295504')
            ->assertStatus(200)
            ->assertJson([
                'city' => 3366,
                'addr' => '槻沢２',
            ]);
        $this->get('/addr/0295512')
            ->assertStatus(200)
            ->assertJson([
                'city' => 3366,
                'addr' => '川尻４',
            ]);
        $this->get('/addr/4536101')
            ->assertStatus(200)
            ->assertJson([
                'city' => 23105,
                'addr' => '',
            ]);
        $this->get('/addr/4536190')
            ->assertStatus(200)
            ->assertJson([
                'city' => 23105,
                'addr' => '',
            ]);
        $this->get('/addr/4980000')
            ->assertStatus(200)
            ->assertJson([
                'city' => 0,
                'addr' => '',
            ]);
        $this->get('/addr/7614101')
            ->assertStatus(200)
            ->assertJson([
                'city' => 37322,
                'addr' => '',
            ]);
        $this->get('/addr/7660001')
            ->assertStatus(200)
            ->assertJson([
                'city' => 37403,
                'addr' => '',
            ]);
        $this->get('/addr/7660002')
            ->assertStatus(200)
            ->assertJson([
                'city' => 37403,
                'addr' => '',
            ]);
        $this->get('/addr/7812100')
            ->assertStatus(200)
            ->assertJson([
                'city' => 39000,
                'addr' => '',
            ]);
        $this->get('/addr/7812110')
            ->assertStatus(200)
            ->assertJson([
                'city' => 39386,
                'addr' => '',
            ]);
        $this->get('/addr/7816410')
            ->assertStatus(200)
            ->assertJson([
                'city' => 39303,
                'addr' => '',
            ]);
        $this->get('/addr/7820000')
            ->assertStatus(200)
            ->assertJson([
                'city' => 39212,
                'addr' => '',
            ]);
        $this->get('/addr/7820003')
            ->assertStatus(200)
            ->assertJson([
                'city' => 39212,
                'addr' => '土佐山田町宮ノ口',
            ]);
        $this->get('/addr/7820077')
            ->assertStatus(200)
            ->assertJson([
                'city' => 39212,
                'addr' => '土佐山田町佐野',
            ]);
        $this->get('/addr/7828502')
            ->assertStatus(200)
            ->assertJson([
                'city' => 39212,
                'addr' => '土佐山田町宮ノ口１８５',
            ]);
        $this->get('/addr/7871107')
            ->assertStatus(200)
            ->assertJson([
                'city' => 39210,
                'addr' => '',
            ]);
        $this->get('/addr/8572427')
            ->assertStatus(200)
            ->assertJson([
                'city' => 42212,
                'addr' => '大島町',
            ]);
    }
}
