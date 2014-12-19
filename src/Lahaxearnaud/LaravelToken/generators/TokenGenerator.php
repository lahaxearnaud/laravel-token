<?php namespace Lahaxearnaud\LaravelToken\Generator;

class TokenGenerator
{
	public function generateSalt()
	{

		return md5(str_random(rand(10, 50)) . time());
	}

	public function generateToken($length = 50)
	{

		return substr(str_shuffle(str_random($length) . $this->generateSalt()), 0, $length);
	}
}