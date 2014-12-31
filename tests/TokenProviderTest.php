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
     * @expectedException \Lahaxearnaud\LaravelToken\exeptions\TokenNotFoundException
     */
    public function testAuthByTokenKO()
    {
        $this->call('GET', '/token/auth');
    }

    /**
     * @author LAHAXE Arnaud <alahaxe@boursorama.fr>
     *
     * @expectedException \Lahaxearnaud\LaravelToken\exeptions\TokenNotFoundException
     */
    public function testTokenKO()
    {
        $this->call('GET', '/token/simple');
    }

    /**
     * Dummy Token
     */

    /**
     * @author LAHAXE Arnaud <alahaxe@boursorama.fr>
     *
     * @expectedException \Lahaxearnaud\LaravelToken\exeptions\TokenNotFoundException
     */
    public function testAuthByTokenDummyKO()
    {
        $this->call('GET', '/token/auth', ['token' => 'DUMMY']);
    }

    /**
     * @author LAHAXE Arnaud <alahaxe@boursorama.fr>
     *
     * @expectedException \Lahaxearnaud\LaravelToken\exeptions\TokenNotFoundException
     */
    public function testTokenDummyKO()
    {
        $this->call('GET', '/token/simple', ['token' => 'DUMMY']);
    }


    /**
     * @author LAHAXE Arnaud <alahaxe@boursorama.fr>
     *
     * @expectedException \Lahaxearnaud\LaravelToken\exeptions\TokenNotValidException
     */
    public function testTokenExpireKO()
    {
        $token = App::make('token');
        $obj = $token->find(3);
        $this->call('GET', '/token/simple', ['token' => $token->cryptToken($obj->token)]);
    }

    /**
     * @author LAHAXE Arnaud <alahaxe@boursorama.fr>
     *
     * @expectedException \Lahaxearnaud\LaravelToken\exeptions\TokenNotValidException
     */
    public function testAuthTokenExpireKO()
    {
        $token = App::make('token');
        $obj = $token->find(3);
        $this->call('GET', '/token/auth', ['token' => $token->cryptToken($obj->token)]);
    }

    /**
     * @author LAHAXE Arnaud <alahaxe@boursorama.fr>
     *
     * @expectedException \Lahaxearnaud\LaravelToken\exeptions\NotLoginTokenException
     */
    public function testAuthNotLoginTokenKO()
    {
        $token = App::make('token');
        $obj = $token->find(4);
        $this->call('GET', '/token/auth', ['token' => $token->cryptToken($obj->token)]);
    }

    /**
     * @author LAHAXE Arnaud <alahaxe@boursorama.fr>
     *
     * @expectedException \Lahaxearnaud\LaravelToken\exeptions\UserNotLoggableByTokenException
     */
    public function testAuthNotLoggableKO()
    {
        $token = App::make('token');
        $obj = $token->find(2);
        $this->call('GET', '/token/auth', ['token' => $token->cryptToken($obj->token)]);
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