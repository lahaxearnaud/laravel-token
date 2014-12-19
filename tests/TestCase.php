<?php
use Orchestra\Testbench\TestCase as TestBenchTestCase;

class TestCase extends TestBenchTestCase {

	protected function getPackageProviders() {

		return array('Lahaxearnaud\LaravelToken\LaravelTokenServiceProvider');
	}

	protected function getPackagePath() {

		return realpath(implode(DIRECTORY_SEPARATOR, array(
			__DIR__,
			'..',
			'src',
			'LahaxeArnaud',
			'LaravelToken',
		)));
	}
}