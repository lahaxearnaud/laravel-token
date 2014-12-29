<?php
/**
 * Created by PhpStorm.
 * Author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * Date: 29/12/14
 * Time: 20:25
 */

class TokenProviderTest extends TestCase {

    public function testAuthByTokenKO()
    {
        $this->call('GET', '/token/auth');


        $this->assertResponseStatus(401);
    }


    public function testTokenKO()
    {
        $this->call('GET', '/token/simple');

        $this->assertResponseStatus(401);
    }
/**
    public function testTokenOK()
    {
        $token = App::make('token');

        $obj = $token->create(1);

        $this->call('GET', '/token/simple', ['token' => $obj->token]);

        $this->assertResponseStatus(200);
    }
 **/
}