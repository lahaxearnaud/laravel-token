<?php namespace Lahaxearnaud\LaravelToken\Generator;

class TokenGenerator {
	public function generateSalt() {

		return md5(str_random(rand(10, 100)) . '-' . time());
	}

	public function generateToken($length = 100) {

		if ($length < 10) {
			throw new \InvalidArgumentException("You can't generate token with less than 10 chars", 1);
		}

		return substr(str_shuffle(str_random($length) . $this->generateSalt()), 0, $length);
	}
}