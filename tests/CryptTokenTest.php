<?php

use Lahaxearnaud\LaravelToken\Security\TokenCrypt;

class CryptTokenTest extends TestCase {

	public function testCanBeReverseCrypt() {
        $crypt = new TokenCrypt();

		$text = str_random(20);
		$this->assertEquals($text, $crypt->uncrypt($crypt->crypt($text)));
	}

	public function testCanBeReverseEmptyCrypt() {
        $crypt = new TokenCrypt();

		$text = '';
		$this->assertEquals($text, $crypt->uncrypt($crypt->crypt($text)));
	}

	public function testCanBeReverseIntegerCrypt() {
        $crypt = new TokenCrypt();

		$text = 100;
		$this->assertEquals($text, $crypt->uncrypt($crypt->crypt($text)));
	}

	public function testCanBeReverseNullCrypt() {
        $crypt = new TokenCrypt();

		$text = null;
		$this->assertEquals($text, $crypt->uncrypt($crypt->crypt($text)));
	}
}