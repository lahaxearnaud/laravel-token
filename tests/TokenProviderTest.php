<?php
/**
 * Created by PhpStorm.
 * Author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * Date: 29/12/14
 * Time: 20:25
 */

class TokenProviderTest extends TestCase {

    /**
     * No Token
     */
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
     * Dummy Token
     */
    public function testAuthByTokenDummyKO()
    {
        $this->call('GET', '/token/auth', ['token' => 'DUMMY']);


        $this->assertResponseStatus(401);
    }


    public function testTokenDummyKO()
    {
        $this->call('GET', '/token/simple', ['token' => 'DUMMY']);

        $this->assertResponseStatus(401);
    }


    /**
     * Expire
     */
    public function testTokenExpireKO()
    {
        $token = App::make('token');
        $obj = $token->find(3);
        $this->call('GET', '/token/simple', ['token' => $token->cryptToken($obj->token)]);
        $this->assertResponseStatus(401);
    }

    public function testAuthTokenExpireKO()
    {
        $token = App::make('token');
        $obj = $token->find(3);
        $this->call('GET', '/token/auth', ['token' => $token->cryptToken($obj->token)]);
        $this->assertResponseStatus(401);
    }

    /**
     * Not login token
     */
    public function testAuthNotLoginTokenKO()
    {
        $token = App::make('token');
        $obj = $token->find(4);
        $this->call('GET', '/token/auth', ['token' => $token->cryptToken($obj->token)]);
        $this->assertResponseStatus(401);
    }

    /**
     * Not loggable by token
     */
    public function testAuthNotLoggableKO()
    {
        $token = App::make('token');
        $obj = $token->find(2);
        $this->call('GET', '/token/auth', ['token' => $token->cryptToken($obj->token)]);
        $this->assertResponseStatus(401);
    }

    /**
     * OK
     */
    public function testTokenOK()
    {
        $token = App::make('token');
        $obj = $token->find(1);
        $response = $this->call('GET', '/token/simple', ['token' => $token->cryptToken($obj->token)]);
        $this->assertResponseStatus(200);
        $this->assertJson($response->getContent());
        $response = json_decode($response->getContent());
        $this->assertEquals(1, $response->token->id);
    }

    public function testAuthTokenOK()
    {
        $token = App::make('token');
        $obj = $token->find(1);
        $response =  $this->call('GET', '/token/auth', ['token' => $token->cryptToken($obj->token)]);
        $this->assertResponseStatus(200);
        $this->assertJson($response->getContent());
        $response = json_decode($response->getContent());
        $this->assertEquals(1, $response->user->id);
        $this->assertEquals(1, $response->token->id);
    }

    /**
     * Ok by header
     */
    public function testTokenHeaderOK()
    {
        $token = App::make('token');
        $obj = $token->find(1);
        $this->call('GET', '/token/simple', array(), array(),['HTTP_token' => $token->cryptToken($obj->token)]);
        $this->assertResponseStatus(200);
    }
}