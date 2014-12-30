<?php

class TokenRepositoryTest extends TestCase
{

    public function testCreate ()
    {
        $repository = App::make('tokenrepository');
        $this->assertInstanceOf('\Lahaxearnaud\LaravelToken\Models\Token', $repository->getModel());
    }
}