<?php
/**
 * Created by PhpStorm.
 * Author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * Date: 29/12/14
 * Time: 20:25
 */

class TokenTest extends TestCase {

    public function testExpireDateAvailable()
    {
        $token = App::make('token');
        $obj = $token->create(1);
        $dates = $obj->getDates();
        $this->assertContains('expire_at', $dates);
    }

    public function testExpireCarbon()
    {
        $token = App::make('token');
        $obj = $token->create(1);
        $this->assertInstanceOf('\Carbon\Carbon', $obj->expire_at);
    }
}