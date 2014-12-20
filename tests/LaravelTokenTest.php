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
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
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
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function testfindByTokenNotFound ()
    {
        $token = App::make('token');

        $token->findByToken('azertyuiop', -1);
    }

    public function testfindByUser ()
    {
        $token = App::make('token');

        $obj = $token->create(1);
        $token->persist($obj);

        $result = $token->findByUser(1);
        $this->assertInstanceOf('\Illuminate\Support\Collection', $result);
        $this->assertEquals(1, count($result));
    }

    public function testfindByUserNoToken ()
    {
        $token = App::make('token');

        $this->assertInstanceOf('\Illuminate\Support\Collection', $token->findByUser(1));
    }
}