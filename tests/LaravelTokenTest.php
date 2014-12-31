<?php

class LaravelTokenTest extends TestCase
{

    public function testCreate ()
    {

        $token = App::make('token');

        $obj = $token->create(1);
        $this->assertTrue($token->persist($obj));
        $this->assertTrue(is_numeric($obj->id) && $obj->id > 0);
    }


    public function testBurn ()
    {

        $token = App::make('token');

        $obj = $token->create(1);
        $this->assertTrue($token->persist($obj));

        $this->assertTrue($token->burn($obj));
    }


    public function testfind ()
    {
        $token = App::make('token');

        $obj = $token->create(1);
        $this->assertTrue($token->persist($obj));

        $this->assertInstanceOf('\Lahaxearnaud\LaravelToken\Models\Token', $token->find($obj->id));
    }

    /**
     * @expectedException \Lahaxearnaud\LaravelToken\exeptions\TokenNotFoundException
     */
    public function testfindNotFound ()
    {
        $token = App::make('token');

        $token->find(-1);
    }

    public function testfindByToken ()
    {
        $token = App::make('token');

        $obj = $token->create(1);
        $token->persist($obj);

        $this->assertInstanceOf('\Lahaxearnaud\LaravelToken\Models\Token', $token->findByToken($obj->token, 1));
    }

    /**
     * @expectedException \Lahaxearnaud\LaravelToken\exeptions\TokenNotFoundException
     */
    public function testfindByTokenNotFound ()
    {
        $token = App::make('token');

        $token->findByToken('azertyuiop', -1);
    }

    public function testfindByUser ()
    {
        $token = App::make('token');

        $obj = $token->create(3);

        $result = $token->findByUser(3);
        $this->assertInstanceOf('\Illuminate\Support\Collection', $result);
        $this->assertEquals(1, count($result));
    }

    public function testfindByUserNoToken ()
    {
        $token = App::make('token');

        $this->assertInstanceOf('\Illuminate\Support\Collection', $token->findByUser(1));
    }

    public function testValidityOK()
    {
        $token = App::make('token');

        $obj = $token->create(1);

        $this->assertTrue($token->isValid($obj));
    }

    public function testValidityKo()
    {
        $token = App::make('token');

        $obj = $token->create(1);
        $obj->expire_at = time() - 36000;

        $this->assertFalse($token->isValid($obj));
    }

    public function testValidCryptTokenOK()
    {
        $token = App::make('token');

        $obj = $token->create(1);

        $this->assertTrue($token->isValidCryptToken($token->cryptToken($obj->token), 1));
    }

    /**
     * @expectedException \Lahaxearnaud\LaravelToken\exeptions\TokenNotFoundException
     */
    public function testValidCryptTokenNotFound()
    {
        $token = App::make('token');

        $this->assertFalse($token->isValidCryptToken('dummy', 1));
    }

    public function testValidCryptTokenExpired()
    {
        $token = App::make('token');

        $obj = $token->create(1);
        $obj->expire_at = time() - 36000;
        $token->persist($obj);

        $this->assertFalse($token->isValidCryptToken($token->cryptToken($obj->token), 1));
    }

    public function testValidUnCryptTokenOK()
    {
        $token = App::make('token');

        $obj = $token->create(1);

        $this->assertTrue($token->isValidToken($obj->token, 1));
    }

    /**
     * @expectedException \Lahaxearnaud\LaravelToken\exeptions\TokenNotFoundException
     */
    public function testValidUnCryptTokenNotFound()
    {
        $token = App::make('token');

        $this->assertFalse($token->isValidToken('DUMMY', 1));
    }

    public function testValidUnCryptTokenExpired()
    {
        $token = App::make('token');

        $obj = $token->create(1);
        $obj->expire_at = time() - 36000;
        $token->persist($obj);

        $this->assertFalse($token->isValidToken($obj->token, 1));
    }
}