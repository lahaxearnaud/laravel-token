<?php
/**
 * Created by PhpStorm.
 * Author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * Date: 29/12/14
 * Time: 20:25
 */
use \Lahaxearnaud\LaravelToken\Models\Token;

class ClearTokenCommandTest extends TestCase {

    public function testClearAll()
    {
        $command = App::make('token.clear');
        $command->run(new Symfony\Component\Console\Input\ArrayInput(array('--all' => true)), new Symfony\Component\Console\Output\NullOutput);

        $this->assertEquals(0, Token::count());
    }

    public function testClearExpired()
    {
        $command = App::make('token.clear');
        $command->run(new Symfony\Component\Console\Input\ArrayInput(array()), new Symfony\Component\Console\Output\NullOutput);

        $this->assertEquals(3, Token::count());
    }
}