<?php namespace Lahaxearnaud\LaravelToken\Generator;

interface GeneratorInterface
{
	public function generateSalt();

	public function generateToken($length = 50);
}
