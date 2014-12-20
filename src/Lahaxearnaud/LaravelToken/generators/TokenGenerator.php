<?php namespace Lahaxearnaud\LaravelToken\Generator;

class TokenGenerator {

    /**
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return string
     */
	public function generateSalt() {

		return md5(str_random(rand(10, 100)) . '-' . time());
	}

    /**
     * @param int $length
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return string
     */
	public function generateToken($length = 100) {

		if ($length < 10) {
			throw new \InvalidArgumentException("You can't generate token with less than 10 chars", 1);
		}

		return substr(str_shuffle(str_random($length) . $this->generateSalt()), 0, $length);
	}
}
