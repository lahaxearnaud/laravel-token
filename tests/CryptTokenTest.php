<?php

class CryptTokenTest extends TestCase {

	public function testCanBeReverseCrypt() {
		$token = App::make('token');

		$text = str_random(20);
		$this->assertEquals($text, $token->uncryptToken($token->cryptToken($text)));
	}

	public function testCanBeReverseEmptyCrypt() {
		$token = App::make('token');

		$text = '';
		$this->assertEquals($text, $token->uncryptToken($token->cryptToken($text)));
	}

	public function testCanBeReverseIntegerCrypt() {
		$token = App::make('token');

		$text = 100;
		$this->assertEquals($text, $token->uncryptToken($token->cryptToken($text)));
	}

	public function testCanBeReverseNullCrypt() {
		$token = App::make('token');

		$text = null;
		$this->assertEquals($text, $token->uncryptToken($token->cryptToken($text)));
	}
}