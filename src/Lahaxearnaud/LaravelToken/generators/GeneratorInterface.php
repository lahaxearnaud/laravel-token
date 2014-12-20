<?php namespace Lahaxearnaud\LaravelToken\Generator;

interface GeneratorInterface
{
    /**
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
	public function generateSalt();

    /**
     * @param int $length
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
	public function generateToken($length = 50);
}
