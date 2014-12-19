<?php
use Lahaxearnaud\LaravelToken\Generator\TokenGenerator;

class TokenGeneratorTest extends TestCase {

	public function testSimpleGenerate() {
		$generator = new TokenGenerator();
		$this->assertEquals(100, strlen($generator->generateToken(100)));
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testTooSmallToken() {
		$generator = new TokenGenerator();
		$generator->generateToken(1);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidLengthToken() {
		$generator = new TokenGenerator();
		$generator->generateToken(-1);
	}
}